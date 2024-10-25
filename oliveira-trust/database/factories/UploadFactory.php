<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Upload;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Upload>
 */
class UploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'file_name' => $this->faker->word . '.csv',
            'file_path' => 'uploads/' . $this->faker->word . '.csv',
            'uploaded_at' => now(),
            'TckrSymb' => $this->faker->word,
            'RptDt' => $this->faker->date(),
            'MktNm' => $this->faker->word, 
            'SctyCtgyNm' => $this->faker->word,
            'ISIN' => strtoupper($this->faker->bothify('??##########')), 
            'CrpnNm' => $this->faker->company,
        ];
    }
}
