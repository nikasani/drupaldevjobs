<?php
namespace Drupal\devjobs\JobsController;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
/**
 * Provides route responses for the Example module.
 */
class JobsController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * 
   *   A simple renderable array.
   */
  public function jobsContent() {

    $title = \Drupal::request()->request->get('title');
    $location = \Drupal::request()->request->get('location');
    $checkbox = \Drupal::request()->request->get('full_time');
    //$conditions_array= array();


    $nids = \Drupal::entityQuery('node')
    ->condition('type','devjobs')
    ->condition('title.value', $title, 'CONTAINS')
    ->condition('field_country', $location, 'CONTAINS')
    ->condition('field_part_time', $checkbox, 'CONTAINS')
    ->execute();


   // if(! empty($title)){
     // $conditions_array[] = ['field_job_title.value', $title];
    //}
    //if(! empty($location)){
      //$conditions_array[] = ['field_country.value', $location];
    //}
    // if(count($conditions_array) > 0){
    //   $query = \Drupal::entityQuery('node');
    //   $group = $query->orConditionGroup()
    //     ->condition('field_job_title.value', $title)
    //     ->condition('field_country.value', $location);
    //   $nids = $query
    //     ->condition('type', 'devjobs')
    //     ->condition($group)
    //     ->execute();
    // }else{
    //   $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    //   $nids=$node_storage->getQuery()
    //   ->condition('type', 'devjobs')
    //   ->execute();
    // }
    
    $results = Node::loadMultiple($nids);

$jobs= [];
foreach($nids as $nid){
  $node= Node::load($nid);
  $fid = $node->field_logo->getValue()[0]['target_id'] ?? null;
  $file = File::load($fid);
  $image_uri = $file->getFileUri();
  $style = ImageStyle::load('thumbnail');
  $url = $style->buildUrl($image_uri);
  $jobs[$nid]= [
  'job_logo'=>$url,
  'job_title' =>$node->field_job_title->getValue()[0]['value'],
  'job_type' =>$node->field_part_time->getValue()[0]['value'],
  'job_country' =>$node->field_country->getValue()[0]['value'],
  'job_company_name' =>$node->field_company_name->getValue()[0]['value'],

  'work_time' => $node->field_time->getValue()[0]['value'],
  'id' => $nid,
  ];
}

    return [
      '#theme' => 'jobs',
      '#jobs' => $jobs,
    ];
  }

}

