<?php

namespace App\Core;

/**
 * Class Response
 *
 * This class provides methods for sending HTTP responses in JSON format.
 * It includes methods for sending both success and error responses with various customizations.
 */
class Response {
    /**
     * Send a JSON response to the client.
     *
     * This method formats the response as JSON and sends it to the client.
     * It sets the HTTP status code, response message, and any data to be included in the response.
     * It also terminates the script after sending the response.
     *
     * @param int $statusCode The HTTP status code to send (default is 200).
     * @param string $message A message to include in the response (optional).
     * @param array $dados The data to include in the response (optional).
     * @param string $status The status of the response (e.g., 'success' or 'error').
     * @return void
     */
    public static function json(
        int $statusCode = 200,
        array $dados = [],
        string $status = 'success'
    ): void {
        // Set the HTTP response code
        http_response_code($statusCode);

        // Set the content type to JSON
        header('Content-Type: application/json');

        // Encode the response data as JSON and send it
        echo json_encode([
            'status_code' => $statusCode,
            'status' => $status,
            'data' => $dados
        ]);

        // Stop the execution of the script
        exit;
    }

    /**
     * Send a success JSON response.
     *
     * This method sends a success response with status code 200.
     * It internally calls the json() method to send the response.
     *
     * @param string $message A message to include in the response (optional).
     * @param array $dados The data to include in the response (optional).
     * @param string $status The status of the response (default is 'success').
     * @return void
     */
    public static function success(array $data = [], string $status = 'success'): void {
        self::json(200, $data, $status);
    }

    /**
     * Send an error JSON response.
     *
     * This method sends an error response with a custom status code.
     * It internally calls the json() method to send the response.
     *
     * @param string $message A message to include in the error response (optional).
     * @param int $statusCode The HTTP status code to send (default is 400).
     * @param array $dados The data to include in the error response (optional).
     * @param string $status The status of the response (default is 'error').
     * @return void
     */
    public static function error(int $statusCode = 500, array $data = [], string $status = 'internal_server_error'): void {
        self::json($statusCode, $data, $status);
    }
}
