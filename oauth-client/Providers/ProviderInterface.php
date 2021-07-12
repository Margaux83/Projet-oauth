<?php
namespace Providers;

class ProviderInterface
{
    public function __construct(string $client_id, string $client_secret);

    public function getUrl(): string;

    public function getData();

}