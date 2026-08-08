<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Tests\TestCase;

class ExceptionHandlerMessageTest extends TestCase
{
    public function test_it_returns_user_friendly_message_for_foreign_key_error(): void
    {
        $handler = app(\App\Exceptions\Handler::class);

        $exception = new QueryException(
            'delete from employees where id = ?',
            [],
            new \RuntimeException('SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails')
        );

        $response = $handler->render(request(), $exception);

        $this->assertSame(409, $response->status());
        $this->assertSame(
            'This item is being used by another record and cannot be deleted.',
            json_decode($response->getContent(), true)['message']
        );
    }

    public function test_it_returns_generic_message_for_server_error(): void
    {
        $handler = app(\App\Exceptions\Handler::class);

        $response = $handler->render(request(), new \RuntimeException('Unexpected backend failure'));

        $this->assertSame(500, $response->status());
        $this->assertSame(
            'Something went wrong. Please try again later.',
            json_decode($response->getContent(), true)['message']
        );
    }
}
