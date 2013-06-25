<?php

abstract class Basic_TestCase extends ZStore_TestCase {

/*        public function test_Tester()   {
	$config = moxi_functions::get_moxi_stats("netops-dgm-ibr-test-1-chef-production-dm.ca2.zynga.com");
		print $config['vbsagent']['config']['config_received'];
        }

*/
/*       public function test_Basic_Cluster_Setup()
        {
		global $test_machine_list;
		global $moxi_machines;
		cluster_setup::setup_membase_cluster();
		sleep(60);
				print "here";
		foreach($moxi_machines as $id=>$moxi)	{
			$config = moxi_functions::get_moxi_stats($moxi, "proxy");
			print $config['vbsagent']['config']['config_received']."\n";
			$this->assertEquals($config['vbsagent']['config']['config_received'], 1, "Config not received by the moxi on $moxi");
		}
		
      }
*/
	public function test_Pump_Keys()
	{
	global $moxi_machines;
	print_r($moxi_machines);
	}

//Testcase to verify that vbucketmigrator gets respawned by VBA after getting killed
	public function test_Kill_Vbucketmigrator()
	{
		global $test_machine_list;

		$vbucketmigrator_map=vba_functions::get_cluster_vbucket_information();

		vba_functions::kill_vbucketmigrator(0);
		$vbucketmigrator_map=vba_functions::get_cluster_vbucket_information();
		$y=0;
		try
		{
			$test=$vbucketmigrator_map[0];		
		}
		catch (Exception $e)
		{
		echo 'Message: ' .$e->getMessage();
		$y=1;
		}

		$this->assertEquals($y, 1,"Vbucketmigrator not stopped");

		sleep(40);
		$vbucketmigrator_map=vba_functions::get_cluster_vbucket_information();
		try
		{
			print_r($vbucketmigrator_map[0]);
			$x=1;
		}
		catch (Exception $e)
		{
			$x=0;	
			echo 'Message: ' .$e->getMessage();
		}
	
		$this->assertEquals($x, 1, "Vbukcetmigrator not started");
	
	}
	
	public function test_Kill_All_Machine_Vbucketmigrator()
	{
		global $test_machine_list;
		global $secondary_machine_list;
		$machine=$test_machine_list[0];
		$secondary_machine=$secondary_machine_list[0];

		$vbucketmigrator_map1=vba_functions::get_cluster_vbucket_information();
		$var=vba_functions::get_vbuckets_from_server($machine);

		$vbu1=array();	
		$i=0;
		foreach($vbucketmigrator_map1 as $key => $value)
		{	
			if($value['source'] === $machine || $value['source'] === $secondary_machine)
			{
			$vbu1[$i]=$key;
			$i++;
			}
		}
		asort($vbu1);
 	
		$vbuckets=array_keys($var);

		//$vbucket_machine=
	#print_r($vbucketmigrator_machine);
	//print_r($vbucketmigrator_map);
		$command_to_be_executed = "sudo killall vbucketmigrator";
        	remote_function::remote_execution($machine, $command_to_be_executed);
		sleep(40);
		$vbucketmigrator_map2=vba_functions::get_cluster_vbucket_information();
		$vbu2=array();
		$i=0;
		foreach($vbucketmigrator_map2 as $key => $value)
        	{
                	if($value['source'] === $machine || $value['source'] === $secondary_machine)
                        {
                        	$vbu2[$i]=$key;
                        	$i++;
                        }
        	}
	        asort($vbu2);
		$diff =array_diff($vbu1,$vbu2);
		echo empty($diff);
		$this->assertEquals(empty($diff),true,"Vbucketmigrator not respawned");
	}
}


class Basic_TestCase_Full  extends Basic_TestCase {

        public function keyProvider() {
                return Data_generation::provideKeys();
        }

        public function keyValueProvider() {
                return Data_generation::provideKeyValues();
        }

        public function keyValueFlagsProvider() {
                return Data_generation::provideKeyValueFlags();
        }
}

?>

