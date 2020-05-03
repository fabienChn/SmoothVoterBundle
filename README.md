# SmoothVoterBundle

## Use 

An example of what a small voter would look like:

    class ThemeVoter extends AbstractSmoothVoter
    {
        /**
         * Allowed actions
         *
         * @var array
         */
        protected $actions = [
            'delete'
        ];
    
        /**
         * @var string
         */
        protected $entityName = Theme::class;
    
        /**
         * @param Theme $theme
         * @param User $user
         * @return bool
         */
        public function canDelete(Theme $theme, User $user): bool
        {
            return $theme->getProfile()->getUser()->getId() === $user->getId();
        }
    }

In the controller, here is how to use the voter:

    public function deleteAction(Article $article, Vote $vote)
    {
        $vote->process(ArticleVoter::class, 'delete', $article); // if the auth is not allowed to delete this article, an exception will be thrown

        $this->removeAndFlush($article);

        return $this->emptyResponse();
    }
