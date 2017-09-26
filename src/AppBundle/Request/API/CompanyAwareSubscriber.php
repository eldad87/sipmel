<?php

namespace AppBundle\Request\API;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 *
 */
class CompanyAwareSubscriber implements EventSubscriberInterface
{
	/**
	 * @var TokenStorage
	 */
	protected $tokenStorage;

	/**
	 * @param $tokenStorage TokenStorage
	 */
	public function __construct(TokenStorageInterface $tokenStorage)
	{
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * @inheritdoc
	 */
	static public function getSubscribedEvents()
	{
		return array(
			array('event' => 'serializer.post_deserialize', 'method' => 'onPostDeserialize'),
		);
	}

	/**
	 * remove cascade=persist
	 * 		@ORM\ManyToOne(targetEntity="Company", inversedBy="variables", cascade={"persist"}, nullable=false)
	 * Set
	 * @param ObjectEvent $event
	 * @return ObjectEvent
	 */
	public function onPostDeserialize(ObjectEvent $event)
	{
		/**
		 * CompanyAware must be set:
		 * @ParamConverter("variable", converter="fos_rest.request_body", "deserializationContext"={"CompanyAware"=true}}})
		 */
		if(!$event->getContext()->attributes->containsKey('CompanyAware') ||
				!$event->getContext()->attributes->get('CompanyAware')->get()) {
			return $event;
		}

		//Check that a user is logged in
		if(!$this->tokenStorage->getToken() || !($user = $this->tokenStorage->getToken()->getUser())) {
			return $event;
		}

		if(!($user instanceof CompanyAwareInterface)) {
			return $event;
		}

		//Check that entity is not set with a company already
		/** @var CompanyAwareInterface $object */
		$object = $event->getObject();
		if(!($object instanceof CompanyAwareInterface) || $object->getCompany()) {
			return $event;
		}

		$object->setCompany($user->getCompany());

		return new ObjectEvent($event->getContext(), $object, $event->getType());
	}
}