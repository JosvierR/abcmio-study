<?php

namespace App\Interfaces;

interface ReportInterface
{
    public CONST OPTIONS = [1 => 'abuse', 2 => 'fake', 3 => 'spam',  4 => 'violence'];
    public CONST LABELS = ['abuse' => 'Abuso de Contenido', 'fake' => 'Anuncio Falso', 'spam' => 'Anuncio Spam', 'violence' => 'Contenido de Violencia'];
    public CONST BANDS = ['abuse' => 'success', 'fake' => 'warning', 'spam'=>'primary', 'violence' => 'danger'];
}