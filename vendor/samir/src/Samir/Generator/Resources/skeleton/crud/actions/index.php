
    /**
     * Lists all {{ entity }} entities.
     *
{% if 'annotation' == format %}
     * @Route("/", name="{{ route_name_prefix }}")
     * @Template()
{% endif %}
     */
    public function indexAction(Application $app)
    {
			$entities = $app['db.orm.em']->getRepository('{{ entity_namespace }}\{{ entity_class }}')
				->findAll();
			
			return $app['twig']->render('{{ entity_class }}\index.html.twig', array(
					'entities' => $entities,
			));
    }
