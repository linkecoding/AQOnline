<?php
	session_start();
	if(@$_SESSION['student'] != null){
		$id = 'student';
		echo "<script>window.location.href = '../student/me.html?id=" . $id . "';</script>";
	}else if (@$_SESSION['teacher'] != null) {
		$id = 'teacher';
		echo "<script>window.location.href = '../teacher/me.html?id=" . $id . "';</script>";
	}else{
		echo "<script>window.location.href = '../error.html';</script>";
	}