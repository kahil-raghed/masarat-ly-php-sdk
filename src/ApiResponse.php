<?php


namespace KahilRaghed\MasaratLy;

class ApiResponse {
    public int $type;
    public string $message;
    public  string $traceId;
    public array $content;

    public function __construct(
        int $type,
        string $message,
        string $traceId,
        array $content
    ){
        $this->type = $type;
        $this->message = $message;
        $this->traceId = $traceId;
        $this->content = $content;
    }


    public static function fromBody(array $data) {
        return new ApiResponse(
            $data['type'] ?? 3,
            $data['message'] ?? "",
            $data['traceId'] ?? "",
            $data['content'] ?? []
        );
    }

    public function success() {
        return $this->type == 1;
    }

    public function error() {
        return $this->type !== 1;
    }
}