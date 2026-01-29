<?php

namespace Database\Seeders;

use App\Models\AiModel;
use Illuminate\Database\Seeder;

class AiModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            [
                'name' => 'gpt-oss-20b',
                'provider' => 'openai',
                'context_length' => 128000,
                'notes' => 'OpenAI GPT OSS 20B model, used for high-context applications',
            ],
            [
                'name' => 'devstral-small-2505',
                'provider' => 'mistralai',
                'context_length' => 32000,
                'notes' => 'Mistral AI Devstral Small 2505 model,    origi used for general-purpose tasks',
            ],
        ];

        foreach ($models as $model) {
            AiModel::create($model);
        }

        $this->command->info('Successfully seeded ' . count($models) . ' AI models!');
    }
}
