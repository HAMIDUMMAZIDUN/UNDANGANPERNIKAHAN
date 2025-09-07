{{-- 
    File: resources/views/undangan/show.blade.php
    Tujuan: Memanggil template undangan yang benar untuk link tamu spesifik.
--}}
@include('undangan.templates.' . $event->template_name)