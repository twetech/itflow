<?php

/*
 * ITFlow - GET/POST request handler for AI Functions
 */

if (isset($_GET['ai_reword'])) {

    header('Content-Type: application/json');

    // Collecting the input data from the AJAX request.
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); // Convert JSON into array.

    // Prefix the input text with "reword: "
    $prefixedText = "You are a technician at a help desk, training another technician. This is a response to the following issue " . $input['description'] . " Help me reword this for clarity and proffesionalism: " . $input['text'];

    // Preparing the data for the OpenAI Chat API request.
    $data = [
        "model" => "$config_ai_model", // Specify the model
        "messages" => [
            ["role" => "user", "content" => $prefixedText]
        ],
        "temperature" => 0.7
    ];

    // Initialize cURL session to the OpenAI Chat API.
    $ch = curl_init("$config_ai_url/v1/chat/completions");

    // Set cURL options for the request.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $config_ai_api_key,
    ]);

    // Execute the cURL session and capture the response.
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response.
    $responseData = json_decode($response, true);

    // Check if the response contains the expected data and return it.
    if (isset($responseData['choices'][0]['message']['content'])) {
        echo json_encode(['rewordedText' => trim($responseData['choices'][0]['message']['content'])]);
    } else {
        // Handle errors or unexpected response structure.
        echo json_encode(['rewordedText' => 'Failed to get a response from the OpenAI API.']);
    }

}