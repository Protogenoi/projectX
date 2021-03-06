<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19df83ba9961325829467802a86119db
{
    public static $files = array(
        '3f8bdd3b35094c73a26f0106e3c0f8b2' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/SendGrid.php',
    );

    public static $prefixLengthsPsr4 = array(
        'T' =>
            array(
                'Twilio\\' => 7,
            ),
        'S' =>
            array(
                'SendGrid\\Stats\\' => 15,
                'SendGrid\\Mail\\' => 14,
                'SendGrid\\Contacts\\' => 18,
                'SendGrid\\' => 9,
            ),
    );

    public static $prefixDirsPsr4 = array(
        'Twilio\\' =>
            array(
                0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
            ),
        'SendGrid\\Stats\\' =>
            array(
                0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/stats',
            ),
        'SendGrid\\Mail\\' =>
            array(
                0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/mail',
            ),
        'SendGrid\\Contacts\\' =>
            array(
                0 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/contacts',
            ),
        'SendGrid\\' =>
            array(
                0 => __DIR__ . '/..' . '/sendgrid/php-http-client/lib',
                1 => __DIR__ . '/..' . '/sendgrid/sendgrid/lib',
            ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19df83ba9961325829467802a86119db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19df83ba9961325829467802a86119db::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
