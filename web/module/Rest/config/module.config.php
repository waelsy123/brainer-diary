<?php
    return array(
        'router' => array(
            'routes' => array(
                // Route for SOAP requests
                'rest' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '/rest/:controller',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Rest\Controller',
                            'controller'=> '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
// //                            'controller' => 'soap',
//                             'action' => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    // so that it will accept the ?wsdl if requested - else it redirects as a bad route

                ),        
            ),
        ),
        'controllers' => array(
            'invokables' => array(
                'Rest\Controller\Tag' => 'Rest\Controller\TagController',
            ),
        ),
        // 'view_manager' => array(
        //     'template_path_stack' => array(
        //         __DIR__ . '/../view',
        //     ),
        // ),
    );
?>