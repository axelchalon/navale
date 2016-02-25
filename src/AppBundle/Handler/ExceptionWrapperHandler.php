<?php
//AppBundle\Handler\MyExceptionWrapperHandler.php
namespace AppBundle\Handler;

use FOS\RestBundle\Util\ExceptionWrapper;
use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;

class MyExceptionWrapperHandler implements ExceptionWrapperHandlerInterface {

    public function wrap($data)
    {
        /** @var \Symfony\Component\Debug\Exception\FlattenException $exception */
        $exception = $data['exception'];
exit('yay');
        $newException = array(
            'success' => false,
            'exception' => array(
                'exceptionClass' => $exception->getClass(),
                'message' => $data['status_text']
            )
        );

        return $newException;
    }
}