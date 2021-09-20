<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */

     /*
    public function report(\Throwable $e)
    {
        parent::report($e);
    }
    */

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    /*
    public function render($request, Throwable $e)
    {

        if ($this->isHttpException($e))
        {
            if ($request->is('api/*')) {
                return response()->json(['status' => false, 'data' => array('error'=>'Veuillez vérifier l\'URL que vous avez envoyée. Erreur code:'.$e->getStatusCode())],$e->getStatusCode());
            }else{
                return $this->renderHttpException($e);
            }

        }
        return parent::render($request, $e);
    }
    */

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    /*
    protected function renderHttpException(Throwable $e)
    {
        if (view()->exists('pages.error'))
        {

            $code = $e->getStatusCode();

            switch ($code) {
                case '200':
                    $message = 'OK';

                    break;
                case '300':
                    $message = 'Multiple Choices';

                    break;
                case '301':
                    $message = 'Moved Permanently';

                    break;
                case '302':
                    $message = 'Found';

                    break;
                case '304':
                    $message = 'Not Modified';

                    break;
                case '307':
                    $message = 'Temporary Redirect';

                    break;
                case '400':
                    $message = 'Bad Request';

                    break;
                case '401':
                    $message = 'Unauthorized';

                    break;
                case '403':
                    $message = 'Forbidden';

                    break;
                case '404':
                    $message = 'Not Found';

                    break;
                case '410':
                    $message = 'Gone';

                    break;
                case '500':
                    $message = 'Internal Server Error';

                    break;
                case '501':
                    $message = 'Not Implemented';

                    break;
                case '503':
                    $message = 'Service Unavailable';
                    break;
                case '550':
                    $message = 'Permission denied';
                    break;
                default:
                    $message = 'Un erreur est intervenu.';
                    break;
            }
            return Response::view('pages.error',compact('code','message'), $e->getStatusCode());
        }
        else
        {
            return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
        }
    }

   */

}
