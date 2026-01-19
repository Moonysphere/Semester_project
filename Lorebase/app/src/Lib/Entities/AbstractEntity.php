<?php

namespace App\Lib\Entities;

abstract class AbstractEntity {

    abstract public function getId(): int | string;
    
  public function toArray(): array {
    $array = [];
    foreach ($this as $key => $value) {

        if ($value instanceof \DateTimeInterface) {
            // comme ton champ est de type "date"
            $array[$key] = $value->format('Y-m-d');
        } else {
            $array[$key] = $value;
        }
    }
    return $array;
  }
  }

?>
