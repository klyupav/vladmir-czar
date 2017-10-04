<?php

namespace App\Donors;

Class BartecDe_Categories_Eng extends BartecDe_Categories
{
    function __construct()
    {
        $this->donor = class_basename(get_class());
        $this->project = 'bartec.de';
        $this->project_link = 'https://www.bartec.de';
        $this->source = 'https://www.bartec.de/en/products/';
        $this->version_id = 1;
        $this->cookieFile = __DIR__ . '/cookie/' . class_basename(get_class($this)) . '/' . class_basename(get_class($this)) . '.txt';
    }
}