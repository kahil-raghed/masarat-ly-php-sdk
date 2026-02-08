<?php


test("sign_in", function () {
    $result = $this->masaratLy->signIn(
        +getenv('USER_ID'),
        getenv('PIN'),
        +getenv('PROVIDER_ID'),
        +getenv('AUTH_USER_TYPE')
    );
    expect($result->success())->toBeTrue();
});