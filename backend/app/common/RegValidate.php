<?php

class RegValidate {
    public static function validUrl() {
        $patt = '/^(((ht|f)tps?):\/\/)([^!@#$%^&*?.\s-]([^!@#$%^&*?.\s]{0,63}[^!@#$%^&*?.\s])?\.)+[a-z]{2,6}\/?/';
    }
}