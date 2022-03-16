<?php

/**
 * Created by PhpStorm.
 * Developer: Tariq Ayman ( tariq.ayman94@gmail.com )
 * Date: 2/1/20, 1:10 AM
 * Last Modified: 2/1/20, 12:15 AM
 * Project Name: Wafaq
 * File Name: DumyDataSeeder.php
 */

use App\Models\Page;
use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\File;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        File::makeDirectory(public_path("/uploads/"), 0777, true, true);
        File::makeDirectory(public_path("/uploads/sliders/"), 0777, true, true);
        File::makeDirectory(public_path("/uploads/partners/"), 0777, true, true);
        File::makeDirectory(public_path("/uploads/settings/"), 0777, true, true);
        File::makeDirectory(public_path("/uploads/banks/"), 0777, true, true);
        File::makeDirectory(public_path("/uploads/bankTransfers/"), 0777, true, true);

        Page::updateOrCreate([
            'slug' => 'about'
        ], [
            'slug' => 'about',
            'title' => 'عن عالم االدخار',
            'title_en' => 'About Allam Aladkhar',
            'content' => 'عالم االدخار هي مؤسسة تجارية مرخصة من وزارة التجارة برقم:5900125884 ومسجلة في منصة معروف برقم 192601 وهي مهتمة في الحلول المالية وتعزيز ثقافة االدخار',
            'content_en' => 'Savings World is a commercial establishment licensed by the Ministry of Commerce with No.: 5900125884 and registered in Maarouf platform No. 192601 and is interested in financial solutions and promoting a culture of savings',
        ]);
        Page::updateOrCreate([
            'slug' => 'goals'
        ], [
            'slug' => 'goals',
            'title' => 'أهداف عالم االدخار',
            'title_en' => 'Allam Aladkhar Goals',
            'content' => 'رفع نسبة ادخار الشعب السعودي للمستوى العالمي وتحسين جودة الحياة لهم وتعزيز ثقافة االدخار المالي',
            'content_en' => 'Raising the Saudi people’s savings rate to the global level, improving the quality of life for them, and promoting a culture of financial savings',
        ]);
        Page::updateOrCreate([
            'slug' => 'terms'
        ], [
            'slug' => 'terms',
            'title' => 'الشروط والاحكام',
            'title_en' => 'Terms & Conditions'
        ]);
        Page::updateOrCreate([
            'slug' => 'privacy'
        ], [
            'slug' => 'privacy',
            'title' => 'سياسة الخصوصية',
            'title_en' => 'Privacy Policy'
        ]);
        Page::updateOrCreate([
            'slug' => 'faqs'
        ], [
            'slug' => 'faqs',
            'title' => 'الاسئلة الشائعة',
            'title_en' => 'FAQs'
        ]);

        Partner::updateOrCreate([
            'image' => '20210605160908-kWrq8ipOfT.png'
        ], [
            'name' => 'شريك 1',
            'image' => '20210605160908-kWrq8ipOfT.png'
        ]);
        Partner::updateOrCreate([
            'image' => '20210605160932-TFka6lmW8Q.png'
        ], [
            'name' => 'شريك 2',
            'image' => '20210605160932-TFka6lmW8Q.png'
        ]);
        Partner::updateOrCreate([
            'image' => '20210605160943-PKsb05z6U6.png'
        ], [
            'name' => 'شريك 3',
            'image' => '20210605160943-PKsb05z6U6.png'
        ]);
        Partner::updateOrCreate([
            'image' => '20210605160956-qFaeoyVx90.png'
        ], [
            'name' => 'شريك 4',
            'image' => '20210605160956-qFaeoyVx90.png'
        ]);
        Partner::updateOrCreate([
            'image' => '20210605161008-fqmyZDoSGE.png'
        ], [
            'name' => 'شريك 5',
            'image' => '20210605161008-fqmyZDoSGE.png'
        ]);
        Partner::updateOrCreate([
            'image' => '20210605161021-LRVPIGLyC6.png'
        ], [
            'name' => 'شريك 6',
            'image' => '20210605161021-LRVPIGLyC6.png'
        ]);


        Setting::updateOrCreate([
            'key' => 'logo',
        ], [
            'value' => '20210605161147-v63qVOrN7T.png',
            'group' => 'general',
            'name' => 'logo',
            'type' => 'file'
        ]);

        Setting::updateOrCreate([
            'key' => 'favicon',
        ], [
            'value' => '20210605161539-GnPFgPDJ0t.png',
            'group' => 'general',
            'name' => 'favicon',
            'type' => 'file'
        ]);
        Setting::updateOrCreate([
            'key' => 'private_circle_commission',
        ], [
            'value' => 0,
            'group' => 'general',
            'name' => 'privat_circle_commission',
            'type' => 'float'
        ]);
        Setting::updateOrCreate([
            'key' => 'public_circle_commission',
        ], [
            'value' => 0,
            'group' => 'general',
            'name' => 'public_circle_commission',
            'type' => 'float'
        ]);
        Setting::updateOrCreate([
            'key' => 'instrument',
        ], [
            'value' => 40,
            'group' => 'general',
            'name' => 'instrument',
            'type' => 'float'
        ]);

        Setting::updateOrCreate([
            'key' => 'private_commission_type',
        ], [
            'value' => 1,
            'group' => 'general',
            'name' => 'privat_commission_type',
            'type' => 'int'
        ]);
        Setting::updateOrCreate([
            'key' => 'public_commission_type',
        ], [
            'value' => 1,
            'group' => 'general',
            'name' => 'public_commission_type',
            'type' => 'int'
        ]);
        Setting::updateOrCreate([
            'key' => 'maintenance',
        ], [
            'value' => 0,
            'group' => 'general',
            'name' => 'maintenance',
            'type' => 'int'
        ]);
        Setting::updateOrCreate([
            'key' => 'phone',
        ], [
            'value' => '0502033914',
            'group' => 'general',
            'name' => 'phone',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'facebook',
        ], [
            'value' => 'https://facebook.com',
            'group' => 'general',
            'name' => 'facebook',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'whatsapp',
        ], [
            'value' => 'https://whatsapp.com',
            'group' => 'general',
            'name' => 'whatsapp',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'snapchat',
        ], [
            'value' => 'https://snapchat.com',
            'group' => 'general',
            'name' => 'snapchat',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'telegram',
        ], [
            'value' => 'https://telegram.com',
            'group' => 'general',
            'name' => 'telegram',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'email',
        ], [
            'value' => 'info@example.com',
            'group' => 'general',
            'name' => 'email',
            'type' => 'string'
        ]);

        Setting::updateOrCreate([
            'key' => 'twitter',
        ], [
            'value' => 'https://twitter.com',
            'group' => 'general',
            'name' => 'twitter',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'instagram',
        ], [
            'value' => 'https://instagram.com',
            'group' => 'general',
            'name' => 'instagram',
            'type' => 'string'
        ]);
        Setting::updateOrCreate([
            'key' => 'address',
        ], [
            'value' => 'السعودية',
            'group' => 'general',
            'name' => 'address',
            'type' => 'string'
        ]);

        Setting::updateOrCreate([
            'key' => 'map',
        ], [
            'value' => '',
            'group' => 'general',
            'name' => 'map',
            'type' => 'text'
        ]);
    }
}
