<?php

namespace App\Core;

/**
 * Class Request
 *
 * This class provides methods for capturing and processing HTTP request data,
 * including retrieving the request URI, method, input data, and validating required fields.
 */
class Request {
    /**
     * Capture the current HTTP request.
     *
     * This method initializes a new instance of the Request class.
     * It can be used to handle the current request context.
     *
     * @return self A new instance of the Request class.
     */
    public static function capture(): self {
        return new static();
    }

    /**
     * Get the URI of the current request (excluding query parameters).
     *
     * This method retrieves the URI part of the request and removes any query parameters.
     *
     * @return string The request URI, excluding any query string.
     */
    public function uri(): string {
        $uri = $_SERVER['REQUEST_URI'];
        return strtok($uri, '?');
    }

    /**
     * Get the HTTP method of the current request.
     *
     * This method retrieves the HTTP method (GET, POST, PUT, DELETE, etc.) of the request.
     *
     * @return string The HTTP method of the request (e.g., GET, POST).
     */
    public function method(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the input data from the request's body (assumed to be JSON).
     *
     * This method retrieves and decodes the JSON input from the request body, 
     * and returns it as an associative array. It also sanitizes string values.
     *
     * If a specific key is provided, it returns the value of that key, 
     * or null if the key does not exist.
     *
     * @param string|null $key The specific key to retrieve from the input data (optional).
     * @return mixed|null The input data (either an array or the value of a specific key).
     */
    public static function input(?string $key = null) {
        $rawInput = file_get_contents('php://input');

        $data = json_decode($rawInput, true);

        // Check if the JSON input is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            \App\Core\Response::error('Invalid JSON: ' . json_last_error_msg(), 400, [], 'bad_request');
        }

        // Sanitize the input values
        $clean = [];
        foreach ($data as $k => $v) {
            $clean[$k] = is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v;
        }

        // Return the specific key's value or all the cleaned data
        return $key ? $clean[$key] ?? null : $clean;
    }

    /**
     * Validate that all required fields are present in the input data.
     *
     * This method checks if all the fields listed in the requiredFields array 
     * are present in the inputFields array. If any required field is missing, 
     * it triggers an error response.
     *
     * @param array $requiredFields An array of field names that must be present in the input data.
     * @param array $inputFields An array of fields from the request input data.
     * @return bool Returns true if all required fields are present.
     * @throws \App\Core\Response::error If a required field is missing.
     */
    public static function validateFields(array $requiredFields, array $inputFields): bool {
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $inputFields)) {
                \App\Core\Response::error("Required field '{$field}' was not sent.", 400, [], 'bad_request');
            }
        }

        return true;
    }
}
