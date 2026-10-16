<?php

namespace Database\Factories;

use App\Enums\LeadIntent;
use App\Enums\LeadServiceInterest;
use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact' => fake()->safeEmail(),
            'service' => LeadServiceInterest::Web,
            'intent' => LeadIntent::Request,
            'problem' => fake()->sentence(12),
            'status' => LeadStatus::New,
            'source' => 'website',
            'ip_address' => fake()->ipv4(),
        ];
    }
}
