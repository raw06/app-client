<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class QASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startProductId = 1; // Define the starting product ID
        $endProductId = 17; // Define the ending product ID
        $productIds = range($startProductId, $endProductId);
        $shopId = 1;

        $faker = Faker::create();

        foreach ($productIds as $productId) {
            $questionCount = rand(3, 7);
            for ($i = 0; $i < $questionCount; $i++) {
                $date = $faker->dateTimeBetween('-1 year', 'now');

                $questionContent = $faker->realText(rand(30, 150));
                $cleanedQuestionContent = str_replace(['"', "'"], '', $questionContent);

                $question = Question::create([
                    'shop_id' => $shopId,
                    'product_id' => $productId,
                    'author' => $faker->name,
                    'email' => $faker->email,
                    'content' => $cleanedQuestionContent,
                    'date' => $date,
                    'status' => rand(0, 3),
                    'pinned_at' => $faker->optional(0.2)->dateTimeBetween($date, 'now'),
                    'source' => 'custom'
                ]);

                $answerCount = rand(7, 14);
                for ($j = 0; $j < $answerCount; $j++) {
                    $answerDate = $faker->dateTimeBetween($date, 'now');
                    $isVerified = $faker->boolean();
                    $isAdmin = !$isVerified && $faker->boolean();

                    $answerContent = $faker->realText(rand(50, 300));
                    $cleanedAnswerContent = str_replace(['"', "'"], '', $answerContent);

                    Answer::create([
                        'shop_id' => $shopId,
                        'question_id' => $question->id,
                        'author' => $faker->name,
                        'email' => $faker->email,
                        'content' => $cleanedAnswerContent,
                        'date' => $answerDate,
                        'status' => rand(0, 3),
                        'is_verified' => $isVerified,
                        'is_admin' => $isAdmin,
                        'pinned_at' => $faker->optional(0.2)->dateTimeBetween($answerDate, 'now'),
                        'source' => 'custom'
                    ]);
                }
            }
        }
    }
}
