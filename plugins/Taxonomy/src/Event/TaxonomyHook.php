<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Taxonomy\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

/**
 * Taxonomy Hook Listener.
 *
 * Handles the "term" search operator.
 */
class TaxonomyHook implements EventListenerInterface
{

    /**
     * Return a list of implemented events.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'SearchableBehavior.operatorTerm' => 'operatorTerm'
        ];
    }

    /**
     * Handles the "term:" search operator. Which filters all entities matching
     * a given collection of terms.
     *
     *     term:cat,dog,bird,...,term-slug
     *
     * You can provide up to 10 terms as maximum.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query The query being scoped
     * @param \Search\Token $token Operator token
     * @return \Cake\ORM\Query Scoped query
     */
    public function operatorTerm(Event $event, $query, $token)
    {
        $slugs = explode(',', $token->value());
        $slugs = array_slice($slugs, 0, 10);

        if (!empty($slugs)) {
            $IN = $token->negated() ? 'NOT IN' : 'IN';
            $table = $event->subject();
            $pk = $table->primaryKey();
            $tableAlias = $table->alias();
            $termsIds = TableRegistry::get('Taxonomy.Terms')
                ->find()
                ->select(['id'])
                ->where(['Terms.slug IN' => $slugs])
                ->all()
                ->extract('id')
                ->toArray();
            $termsIds = empty($termsIds) ? [0] : $termsIds;
            $subQuery = TableRegistry::get('Taxonomy.EntitiesTerms')
                    ->find()
                    ->select(['entity_id'])
                    ->where(['term_id IN' => $termsIds, 'table_alias' => $tableAlias]);

            if ($token->where() === 'or') {
                $query->orWhere(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
            } elseif ($token->where() === 'and') {
                $query->andWhere(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
            } else {
                $query->where(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
            }
        }

        return $query;
    }
}
