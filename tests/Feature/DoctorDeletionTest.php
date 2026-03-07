<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Employee;
use App\Models\Contact;
use App\Models\Patient;
use App\Models\Appointment;

class DoctorDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_deletion_also_removes_contact_and_user()
    {
        // create a clinic and the authenticated user
        $clinic = Clinic::create(['name' => 'Test Clinic', 'status' => 'ACTIVE']);
        $actor = User::factory()->create(['clinic_id' => $clinic->id]);

        $this->actingAs($actor);

        // create a new doctor via the API
        $payload = [
            'password' => 'secret123',
            'code' => 'D001',
            'contact' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '1234567890',
            ],
        ];

        $response = $this->postJson('/api/doctors', $payload);
        $response->assertStatus(201);

        $doctor = $response->json();
        $doctorId = $doctor['id'];

        // verify records exist before deletion
        $employee = Employee::find($doctorId);
        $this->assertNotNull($employee, 'Employee record was not created');
        $contactId = $employee->contact_id;
        $this->assertNotNull($contactId, 'Contact id not set on employee');

        $this->assertDatabaseHas('contacts', ['id' => $contactId]);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // delete the doctor
        $deleteResponse = $this->deleteJson("/api/doctors/{$doctorId}");
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['message' => 'Doctor and related records deleted successfully']);

        // confirm cascading removal
        $this->assertDatabaseMissing('employees', ['id' => $doctorId]);
        $this->assertDatabaseMissing('contacts', ['id' => $contactId]);
        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }

    public function test_inactivating_doctor_marks_user_inactive()
    {
        // create a clinic and the authenticated user
        $clinic = Clinic::create(['name' => 'Test Clinic', 'status' => 'ACTIVE']);
        $actor = User::factory()->create(['clinic_id' => $clinic->id]);
        $this->actingAs($actor);

        // create a new doctor via the API
        $payload = [
            'password' => 'secret123',
            'code' => 'D003',
            'contact' => [
                'first_name' => 'Alex',
                'last_name' => 'Turner',
                'email' => 'alex@example.com',
                'mobile' => '1112223333',
            ],
        ];
        $response = $this->postJson('/api/doctors', $payload);
        $response->assertStatus(201);
        $doctorId = $response->json()['id'];

        // update status to inactive
        $updateResponse = $this->patchJson("/api/doctors/{$doctorId}", ['status' => 'INACTIVE']);
        $updateResponse->assertStatus(200);

        // verify employee and user statuses
        $this->assertDatabaseHas('employees', ['id' => $doctorId, 'status' => 'INACTIVE']);
        $this->assertDatabaseHas('users', ['email' => 'alex@example.com', 'status' => 'INACTIVE']);
    }

    public function test_prevent_deletion_if_doctor_has_appointments()
    {
        // create clinic and acting user
        $clinic = Clinic::create(['name' => 'Test Clinic', 'status' => 'ACTIVE']);
        $actor = User::factory()->create(['clinic_id' => $clinic->id]);
        $this->actingAs($actor);

        // create doctor via API
        $payload = [
            'password' => 'secret123',
            'code' => 'D002',
            'contact' => [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'mobile' => '0987654321',
            ],
        ];
        $response = $this->postJson('/api/doctors', $payload);
        $response->assertStatus(201);
        $doctorId = $response->json()['id'];

        // create a patient to attach appointment
        $contact = Contact::create([
            'first_name' => 'Patient',
            'last_name' => 'One',
            'email' => 'patient@example.com',
            'mobile' => '5555555555',
        ]);
        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'contact_id' => $contact->id,
            'status' => 'ACTIVE',
        ]);

        // create appointment for the doctor
        Appointment::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
            'status' => 'ACTIVE',
        ]);

        // attempt to delete
        $deleteResponse = $this->deleteJson("/api/doctors/{$doctorId}");
        $deleteResponse->assertStatus(400);
        $deleteResponse->assertJson(['message' => 'Doctor has existing appointments. Please mark the doctor inactive instead of deleting.']);

        // doctor should still exist
        $this->assertDatabaseHas('employees', ['id' => $doctorId]);
    }
}
