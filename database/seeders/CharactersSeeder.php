<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class CharactersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = [
            [
                'name' => 'Dr. Elena Voss',
                'personality_description' => 'A brilliant but pragmatic scientist with a sharp wit and analytical mind. She approaches problems methodically and values logic over emotion. Can be dismissive of ideas she considers unscientific, but deeply cares about finding the truth. Often uses technical jargon and references research papers in conversation.'
            ],
            [
                'name' => 'Marcus "Tech" Chen',
                'personality_description' => 'An energetic and optimistic tech enthusiast who loves gadgets and innovation. Always eager to share his latest discoveries and tends to speak quickly when excited. Has a friendly, approachable demeanor but can be naive about people\'s darker intentions. Often tries to lighten tense situations with humor.'
            ],
            [
                'name' => 'Captain Sarah Blackwood',
                'personality_description' => 'A stern military veteran with a no-nonsense attitude and strong sense of duty. Values discipline, order, and protocol above all else. Has a commanding presence and expects others to follow her lead. Beneath her tough exterior, she has a protective instinct for her team. Speaks in short, decisive sentences.'
            ],
            [
                'name' => 'Aria Moonlight',
                'personality_description' => 'A creative and empathetic artist with a dreamy, introspective nature. She perceives the world through emotions and symbolism rather than pure logic. Often lost in thought and speaks in poetic, metaphorical language. Very intuitive about people\'s feelings but can be indecisive when faced with harsh realities.'
            ],
            [
                'name' => 'Victor Sterling',
                'personality_description' => 'A charismatic and cunning businessman with a silver tongue and calculated charm. Always thinking several steps ahead and views most interactions as negotiations. Confident to the point of arrogance, but has the skills to back it up. Can be manipulative when it serves his interests, yet surprisingly loyal to those he considers valuable allies.'
            ]
        ];

        foreach ($characters as $character) {
            Character::create($character);
        }
         $this->command->info('Successfully seeded 5 characters!');
    }
}
