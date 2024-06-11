<?php   

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Event\PostSubmitEvent;
use App\Entity\Category;
use App\Entity\Recipe;

class FormListenerFactory
{
    public function autoslug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();
            if (empty($data['slug'])) {
                $slugger = new AsciiSlugger();
                $data['slug'] = strtolower($slugger->slug($data[$field]));
                $event->setData($data);
            }
        };
    }

    public function autodate(): callable
    {
        return function (PostSubmitEvent $event) {
            $data = $event->getData();
            if ($data instanceof Category || $data instanceof Recipe) {
                $data->setUpdatedAt(new \DateTimeImmutable());
                if (!$data->getId()) {
                    if ($data instanceof Category) {
                        $data->setCreatedAt(new \DateTimeImmutable());
                    } else {
                        $data->setCreateAt(new \DateTimeImmutable());
                    }
                } else {
                    return;
                }
            }
        };
    }
}
