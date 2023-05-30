<?php


namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class Reply
{

    /** Return success response
     * @param $message
     * @return JsonResponse
     */
    public static function success($message) : JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => Reply::getTranslated($message)
        ]);
    }

    public static function successWithData($message, $data)
    {
        $response = Reply::success($message);

        return array_merge($response->getOriginalContent(), $data);
    }

    /** Return error response
     * @param $message
     * @param null $error_name
     * @param array $errorData
     * @param int $code
     * @param array $errors
     * @return JsonResponse
     */
    public static function error(
        $message,
        $error_name = null,
        array $errorData = [],
        int $code = 422,
        array $errors = []) : JsonResponse
    {
        $response = [
            'status' => 'fail',
            'error_name' => $error_name,
            'data' => $errorData,
            'errors' => $errors,
            'message' => Reply::getTranslated($message)
        ];
        return response()->json($response, $code);

    }

    /** Return validation errors
     * @param \Illuminate\Validation\Validator|Validator $validator
     * @return array
     */
    public static function formErrors($validator)
    {
        return [
            'status' => 'fail',
            'errors' => $validator->getMessageBag()->toArray()
        ];
    }

    /** Response with redirect action. This is meant for ajax responses and is not meant for direct redirecting
     * to the page
     * @param $url string to redirect to
     * @param null $message Optional message
     * @return array
     */
    public static function redirect(string $url, $message = null) : array
    {

        if ($message) {
            return [
                'status' => 'success',
                'message' => Reply::getTranslated($message),
                'action' => 'redirect',
                'url' => $url
            ];
        } else {
            return [
                'status' => 'success',
                'action' => 'redirect',
                'url' => $url
            ];
        }
    }

    private static function getTranslated($message)
    {
        $trans = trans($message);

        if ($trans == $message) {
            return $message;
        } else {
            return $trans;
        }
    }


    public static function dataOnly($data) : JsonResponse
    {
        return response()->json($data);
    }

    public static function redirectWithError($url, $message = null)
    {
        if ($message) {
            return [
                'status' => 'fail',
                'message' => Reply::getTranslated($message),
                'action' => 'redirect',
                'url' => $url
            ];
        } else {
            return [
                'status' => 'fail',
                'action' => 'redirect',
                'url' => $url
            ];
        }
    }

}
