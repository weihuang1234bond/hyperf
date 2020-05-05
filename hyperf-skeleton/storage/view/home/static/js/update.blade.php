<!doctype html>
<html>

@include('home.head')


@include('home.left')
<!--这里-->
@extends('home.{{$res['view']}}')
@include('home.foot');