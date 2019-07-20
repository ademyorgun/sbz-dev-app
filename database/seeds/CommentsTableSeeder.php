<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('comments')->insert([
            'body' => Str::random(10),
            'user_id' => 1,
            'appointment_id' => 147,
        ]);
    }
}
