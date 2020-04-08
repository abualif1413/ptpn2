<?php

$config = array(

    // Database settings
    
    'dbHost'          => '127.0.0.1',
    'dbPort'          => 3306,
    'dbUser'          => 'asikinon_ptpn2',
    'dbPassword'      => 'asikinon_ptpn2',
    'dbName'          => 'asikinon_ptpn2',
    
    // Chat application admin user
    
    'superUser' => 'admin',
    'superPass' => 'admin123',
    
    // Other (do not modify manually)

    'dbType' => 'mysql',
    
    'avatarImageSize' => 40,
    
    'defaultSettings' => array(
    
        'primaryColor'          => '#36a9e1',
        'secondaryColor'        => '#86C953',
        'labelColor'            => '#ffffff',
        'hideWhenOffline'       => true,
        'contactMail'           => 'admin@domain.com',
        'loadingLabel'          => 'Loading...',
        'loginError'            => 'Can\'t login',
        'chatHeader'            => 'Live Support',
        'startInfo'             => 'Silahkan isi data Anda untuk memulai',
        'maxConnections'        => 5,
        'messageSound'          => 'audio/default.mp3',
        'startLabel'            => 'Mulai',
        'backLabel'             => 'Kembali',
        'initMessageBody'       => 'Hallo, Ada yang bisa kami bantu?',
        'initMessageAuthor'     => 'Operator',
        'chatInputLabel'        => 'Tulis disini',
        'timeDaysAgo'           => 'day(s) ago',
        'timeHoursAgo'          => 'hour(s) ago',
        'timeMinutesAgo'        => 'minute(s) ago',
        'timeSecondsAgo'        => 'second(s) ago',
        'offlineMessage'        => 'Operator sedang Offline',
        'toggleSoundLabel'      => 'Sound effects',
        'toggleScrollLabel'     => 'Auto-scroll',
        'toggleEmoticonsLabel'  => 'Emoticons',
        'toggleAutoShowLabel'   => 'Auto-show',
        'contactHeader'         => 'Hubungi Kami',
        'contactInfo'           => 'Semua Operator Kami sedang Offline, Silahkan kirim keluhan Anda.',
        'contactNameLabel'      => 'Nama Anda',
        'contactMailLabel'      => 'E-mail Anda',
        'contactQuestionLabel'  => 'Keluhan Anda',
        'contactSendLabel'      => 'Kirim',
        'contactSuccessHeader'  => 'Message sent',
        'contactSuccessMessage' => 'Keluhan sudah kami terima. Thank you!',
        'contactErrorHeader'    => 'Error',
        'contactErrorMessage'   => 'There was an error sending your question'
    )
);

// Generate connection strings

$config['dbConnectionRaw_mysql'] = 'mysql:host=' . $config['dbHost'] . ';port=' . $config['dbPort'];
$config['dbConnection_mysql']    = 'mysql:dbname=' . $config['dbName'] . ';host=' . $config['dbHost'] . ';port=' . $config['dbPort'];

// Used connection strings

$config['dbConnectionRaw'] = $config['dbConnectionRaw_' . $config['dbType']];
$config['dbConnection']    = $config['dbConnection_'    . $config['dbType']];

return $config;
?>
