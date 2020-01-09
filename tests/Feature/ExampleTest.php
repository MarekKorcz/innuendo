<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // test belongtomany dla graphic
//        $employee1 = User::create([
//            'name' => 'Marek',
//            'surname' => 'Korcz',
//            'slug' => str_slug('Marek Korcz'),
//            'phone_number' => '729364873',
//            'email' => 'mark.korcz@gmail.com',
//            'password' => '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq',
//            'isEmployee' => 1
//        ]);
//        
//        $employee2 = User::create([
//            'name' => 'Marek2',
//            'surname' => 'Korcz',
//            'slug' => str_slug('Marek2 Korcz'),
//            'phone_number' => '443456734',
//            'email' => 'mark2.korcz@gmail.com',
//            'password' => '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq',
//            'isEmployee' => 1
//        ]);
//        
//        $employee3 = User::create([
//            'name' => 'Marek3',
//            'surname' => 'Korcz',
//            'slug' => str_slug('Marek3 Korcz'),
//            'phone_number' => '928374023',
//            'email' => 'mark3.korcz@gmail.com',
//            'password' => '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq',
//            'isEmployee' => 1
//        ]);
//        
//        $boss = User::create([
//            'name' => 'Arek',
//            'surname' => 'Talarek',
//            'phone_number' => '123654789',
//            'email' => 'arek.talarek@gmail.com',
//            'password' => '$2y$10$8R3OnzYV7pgIsLvCbRi1.eGMpjYZ.HtEbau5Gqry6YdlE9yxuq2vq',
//            'isBoss' => 1
//        ]);
//        
//        $bossGraphic = Graphic::create([
//            'start_time' => '10:00',
//            'end_time' => '14:00',
//            'total_time' => '240',
//            'day_id' => 1
//        ]);
//        
//        $bossGraphic2 = Graphic::create([
//            'start_time' => '11:00',
//            'end_time' => '15:00',
//            'total_time' => '300',
//            'day_id' => 2
//        ]);
//        
//        $bossGraphic->employees()->attach($employee1);
//        $bossGraphic->employees()->attach($employee2);
//        $bossGraphic->employees()->attach($employee3);
//        
//        $bossGraphic2->employees()->attach($employee1);
//        $bossGraphic2->employees()->attach($employee2);
//        $bossGraphic2->employees()->attach($employee3);
//        
//        dump($employee1->graphics);
//        dump($employee2->graphics);
//        dump($employee3->graphics);
//        die;
        
        
        // test belongtomany dla graphicRequestów
//        $graphicReq = GraphicRequest::create([
//            'start_time' => '11:00',
//            'end_time' => '15:00',
//            'comment' => 'lalala',
//            'property_id' => 2,
//            'day_id' => 2
//        ]);
//        
//        $graphicReq2 = GraphicRequest::create([
//            'start_time' => '10:00',
//            'end_time' => '16:00',
//            'comment' => 'kakaka',
//            'property_id' => 3,
//            'day_id' => 1
//        ]);
//        
//        $graphicReq->employees()->attach($employee1);
//        $graphicReq->employees()->attach($employee2);
//        $graphicReq->employees()->attach($employee3);
//        
//        $graphicReq2->employees()->attach($employee1);
//        $graphicReq2->employees()->attach($employee2);
//        $graphicReq2->employees()->attach($employee3);
//        
//        
//        dump('employees');
//        
//        dump($employee1->graphicRequests);
//        dump($employee2->graphicRequests);
//        dump($employee3->graphicRequests);
//        
//        dump('req1');
//        
//        dump($graphicReq->employees);
//        dump($graphicReq->employees);
//        dump($graphicReq->employees);
//        
//        dump('req2');
//        
//        dump($graphicReq2->employees);
//        dump($graphicReq2->employees);
//        dump($graphicReq2->employees);
//        die;
        
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
