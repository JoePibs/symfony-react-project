<?php
namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Recipe;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Category;
use App\DataFixtures\userFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class recipesFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));
        $categories = ['entree', 'plat', 'dessert', 'fromage', 'boisson'];
        foreach ($categories as $cat) {
            $category = (new Category())
            ->setName($cat)
            ->setSlug($this->slugger->slug($cat))
            ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($category);
            $this->addReference($cat,$category);
        }

        for ($i = 0; $i < 30; $i++) {
            $recipe = new Recipe();
            $title = $faker->foodName();
            $recipe->setTitle($title)
                ->setSlug($this->slugger->slug($title))
                ->setDuration($faker->numberBetween(5, 120))
                ->setCreateAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setIngredients(''.$faker->vegetableName().','.$faker->fruitName().','.$faker->meatName().','.$faker->sauceName().'')
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setContent($faker->paragraphs(3, true))
                ->setDuration($faker->numberBetween(5, 120))
                ->setUser($this->getReference('user_'.$faker->numberBetween(0, 49)));
            $manager->persist($recipe);
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            userFixture::class,
        ];
    }
}