<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // Building Types
            ['config_type' => 'building_type', 'name' => 'School Building', 'sort_order' => 0],
            ['config_type' => 'building_type', 'name' => 'Laboratory', 'sort_order' => 1],
            ['config_type' => 'building_type', 'name' => 'Administration', 'sort_order' => 2],
            ['config_type' => 'building_type', 'name' => 'Gymnasium', 'sort_order' => 3],
            ['config_type' => 'building_type', 'name' => 'Canteen', 'sort_order' => 4],
            
            // Alarm Types
            ['config_type' => 'alarm_type', 'name' => 'Bell'],
            ['config_type' => 'alarm_type', 'name' => 'Mechanical'],
            ['config_type' => 'alarm_type', 'name' => 'Digital'],
            
            // Alarm Statuses
            ['config_type' => 'alarm_status', 'name' => 'Functional', 'color_class' => 'success'],
            ['config_type' => 'alarm_status', 'name' => 'Broken', 'color_class' => 'danger'],
            ['config_type' => 'alarm_status', 'name' => 'Missing', 'color_class' => 'danger'],
            ['config_type' => 'alarm_status', 'name' => 'Not Installed', 'color_class' => 'info'],
            
            // Extinguisher Types
            ['config_type' => 'extinguisher_type', 'name' => 'Dry Chemical (ABC)'],
            ['config_type' => 'extinguisher_type', 'name' => 'CO2'],
            ['config_type' => 'extinguisher_type', 'name' => 'Water'],
            ['config_type' => 'extinguisher_type', 'name' => 'Foam'],
            ['config_type' => 'extinguisher_type', 'name' => 'Clean Agent'],
            
            // Extinguisher Statuses
            ['config_type' => 'extinguisher_status', 'name' => 'Active', 'color_class' => 'success'],
            ['config_type' => 'extinguisher_status', 'name' => 'Expired', 'color_class' => 'danger'],
            ['config_type' => 'extinguisher_status', 'name' => 'For Refill', 'color_class' => 'warning'],
            ['config_type' => 'extinguisher_status', 'name' => 'Damaged', 'color_class' => 'secondary'],
            
            // Safety Features
            ['config_type' => 'safety_feature', 'name' => 'Emergency Lights', 'sort_order' => 0],
            ['config_type' => 'safety_feature', 'name' => 'Fire Exit Signs', 'sort_order' => 1],
            ['config_type' => 'safety_feature', 'name' => 'First Aid Kits', 'sort_order' => 2],
            ['config_type' => 'safety_feature', 'name' => 'Sprinkler System', 'sort_order' => 3],
        ];

        foreach ($configs as $config) {
            \App\Models\SystemConfiguration::create($config);
        }
    }
}
