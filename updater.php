<?php
if (is_admin()) { //nota l'uso si is_admin(), serve per rassicurarci che ci troviamo nel backend
  $config = array(
    'slug' => plugin_basename(__FILE__), // l'abbreviazione del plugin
    'proper_folder_name' => 'custom-registration-fields', // il nome della cartella che contiene il nostro plugin
    'api_url' => 'https://github.com/carlostr97/custom-registration-fields', // la GitHub API url del repository contenente il plugin
    'raw_url' => 'http://raw.github.com/nomeutente/nome-repository/master', // la GitHub raw url del repository contenente il plugin
    'github_url' => 'https://github.com/carlostr97/custom-registration-fields', // la GitHub url del repository contenente il plugin
    'zip_url' => 'https://github.com/carlostr97/custom-registration-fields.gitr', // dove si trova l'archivio .zip del repository
    'sslverify' => true, // se WordPress deve utilizzare un certificato SSL quando effettua il controllo sull'aggiornamento
    'requires' => '3.0', // specificare quale versione di WordPress e' richiesta da questo plugin
    'tested' => '3.3', // fino a che versione di WordPress hai testato il plugin?
    'readme' => 'README.md', // quale file deve essere usato per controllare la versione del plugin?
    'access_token' => '', // serve soltanto quando utilizziamo repository WordPress privati
  );
  new WP_GitHub_Updater($config);
}
?>