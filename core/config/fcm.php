<?php

return [
    'project_id'       => 'shoppingzone-7f656',
    // Stored OUTSIDE the web-served docroot (public/) so the secret is not
    // downloadable over HTTP. dirname(base_path(),2) = .../shoppingzonebd.com.bd.
    'credentials_path' => dirname(base_path(), 1) . '/firebase/service-account.json',
];
