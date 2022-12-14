<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }


    public function home()
    {
        $states=["ACT","NSW","NT","SA","TAS","QLD","VIC","WA"];
        $fueltypes = ["U91","E10","P95","P98","LPG","DL","PDL","LAF"];
        $allresults = ['Status' => '00'];

        foreach ($states as $state){
            $statecluster=[];
            foreach ($fueltypes as $fueltype){
                if($fueltype == "LAF" && $state != "NT"){   // Skipping LAF for other states
                    continue;
                }

                $resultrow = TableRegistry::getTableLocator()->get('Allstations')->find()->where(['state'=>$state])
                    ->select(['brand', 'name', 'address','suburb','state','postcode', 'loc_lat', 'loc_lng', $fueltype])
                    ->whereNotNull($fueltype)->orderAsc($fueltype)->limit(25);
                $statecluster+=[$fueltype=>$resultrow->toArray()];
            }
            $allresults += [strtoupper($state) => $statecluster];
        }
        $this->set(compact('allresults','states','fueltypes'));
    }

    public function dev(){
        // Nothing expected from Controller.
    }

    public function mapl($fueltype = null){
        if($fueltype == null){
            $fueltype="U91";
        }
        $fueltypes = ['U91','E10','P95','P98','DL','PDL','LPG'];
        if (!in_array($fueltype, $fueltypes)){
            // Fuel type not found
            $fueltype="U91";
        }
        // Use fuel type from outside input
        $states=["nsw","tas","wa","sa","nt","qld","vic","act"];
        $priceinfo=[];
        foreach ($states as $state){
            $tablename = ucfirst($state).'fuel';
            $resultrow = TableRegistry::getTableLocator()->get($tablename)->find('all')->select(['brand', 'name', 'address','suburb','postcode', 'loc_lat', 'loc_lng', $fueltype])->whereNotNull($fueltype)->orderAsc($fueltype)->toArray();
            $priceinfo+=[$state=>$resultrow];
//            debug($resultrow);
        }
//        debug($priceinfo);
        $this->set(compact('fueltype','priceinfo'));
    }
}
