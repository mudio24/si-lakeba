<?php
session_start();
session_destroy();
echo "<script>alert('Berhasil logout dari SI-LAKEBA!'); window.location='../index.php';</script>";