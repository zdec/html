<?php

namespace Tests\Feature\Chatbot;

use App\Mail\CustomerTemporaryPasswordMail;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Tests\Support\CreatesAdminData;
use Tests\TestCase;

class ChatbotFlowTest extends TestCase
{
    use CreatesAdminData;

    public function test_guest_can_toggle_wishlist_and_fetch_current(): void
    {
        $product = $this->createProduct();

        $toggle = $this->postJson(route('api.wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $toggle->assertOk()
            ->assertJson(['success' => true, 'liked' => true]);

        $current = $this->getJson(route('api.wishlist.current'));
        $current->assertOk()
            ->assertJson(['success' => true, 'count' => 1]);
    }

    public function test_chatbot_can_create_order_from_confirmed_chat_for_guest(): void
    {
        Mail::fake();
        $product = $this->createProduct(['price' => 3100]);

        $this->postJson(route('api.wishlist.toggle'), [
            'product_id' => $product->id,
        ])->assertOk();

        $session = $this->postJson(route('api.chat.session.start'))
            ->assertOk()
            ->assertJson(['success' => true])
            ->json();

        $response = $this->postJson(route('api.chat.confirm-order'), [
            'session_id' => $session['session_id'],
            'email' => 'chatbot-guest@test.local',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $order = Order::latest('id')->first();
        $this->assertNotNull($order);
        $this->assertEquals('chatbot_web', $order->source);
        $this->assertEquals(Order::STATUS_PEDIDO, $order->status);
        $this->assertEquals(3100, (int) $order->total);
        $this->assertNotNull($order->customer_id);

        $this->assertDatabaseHas('users', [
            'email' => 'chatbot-guest@test.local',
            'is_admin' => false,
            'must_change_password' => true,
        ]);

        Mail::assertQueued(CustomerTemporaryPasswordMail::class);
        Mail::assertQueued(OrderStatusMail::class, function (OrderStatusMail $mail) use ($order) {
            return $mail->order->is($order) && $mail->status === Order::STATUS_PEDIDO;
        });
    }

    public function test_chatbot_creates_order_for_authenticated_customer_without_email_prompt(): void
    {
        Mail::fake();
        $user = $this->createUser(false, ['email' => 'cliente-chat@test.local']);
        $customer = $this->createCustomerFor($user, ['email' => 'cliente-chat@test.local']);
        $product = $this->createProduct(['price' => 2500]);

        $this->actingAs($user)->postJson(route('api.wishlist.toggle'), [
            'product_id' => $product->id,
        ])->assertOk();

        $session = $this->actingAs($user)->postJson(route('api.chat.session.start'))
            ->assertOk()
            ->json();

        $response = $this->actingAs($user)->postJson(route('api.chat.confirm-order'), [
            'session_id' => $session['session_id'],
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'source' => 'chatbot_web',
            'status' => Order::STATUS_PEDIDO,
        ]);
        Mail::assertQueued(OrderStatusMail::class);
    }

    public function test_chatbot_message_returns_intent_products_and_traceability_fields(): void
    {
        $product = $this->createProduct([
            'name' => 'Telefono Empresarial',
            'description' => 'Telefono de alto rendimiento',
            'price' => 4500,
        ]);

        $this->postJson(route('api.chat.session.start'))->assertOk();

        $session = ChatSession::latest('id')->first();
        $this->assertNotNull($session);

        $response = $this->postJson(route('api.chat.message'), [
            'session_id' => $session->id,
            'message' => 'quiero saber de telefonos',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure(['reply', 'intent', 'products', 'fallback_used']);

        $session->refresh();
        $this->assertNotNull($session->ip_address);

        $botMessage = ChatMessage::where('chat_session_id', $session->id)->where('role', 'bot')->latest('id')->first();
        $this->assertNotNull($botMessage);
        $this->assertNotNull($botMessage->ip_address);
        $this->assertNotEmpty(data_get($botMessage->payload, 'intent'));
        $this->assertTrue(str_contains($botMessage->content, '/producto/' . $product->slug) || str_contains($botMessage->content, 'Te ayudo'));
    }

    public function test_chatbot_handles_out_of_scope_message_without_recommending_products(): void
    {
        $this->createProduct([
            'name' => 'Antena Satelital Cobham BGAN',
            'description' => 'Solucion satelital empresarial',
            'price' => 5600,
        ]);

        $this->postJson(route('api.chat.session.start'))->assertOk();
        $session = ChatSession::latest('id')->first();
        $this->assertNotNull($session);

        $response = $this->postJson(route('api.chat.message'), [
            'session_id' => $session->id,
            'message' => 'penes',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $reply = (string) $response->json('reply');
        $this->assertStringContainsString('No tengo informacion al respecto', $reply);
        $this->assertFalse(str_contains($reply, '/producto/'));
    }
}
