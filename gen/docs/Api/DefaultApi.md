# OpenAPI\Client\DefaultApi

All URIs are relative to http://localhost, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**empleadosGet()**](DefaultApi.md#empleadosGet) | **GET** /empleados | Obtiene todos los empleados |


## `empleadosGet()`

```php
empleadosGet(): \OpenAPI\Client\Model\EmpleadosGet200ResponseInner[]
```

Obtiene todos los empleados

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->empleadosGet();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->empleadosGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\OpenAPI\Client\Model\EmpleadosGet200ResponseInner[]**](../Model/EmpleadosGet200ResponseInner.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
