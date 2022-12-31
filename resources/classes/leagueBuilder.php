<?php

class LeagueBuilder
{
	private $participating_teams;
	private $num_teams;
	private $matches_per_day;
	private $days;

	private $fixtures;
			
	private $matchups;

	function __construct($teams) {
		$this->participating_teams = $teams;
		$this->num_teams = count($this->participating_teams);
		$this->matches_per_day = $this->num_teams / 2;
		$this->days = 2 * ($this->num_teams - 1);
		$this->fixtures = array();
		$this->matchups = array();
	}

	public function generateDays() {
		$this->permutationsPer2();
		while(count($this->fixtures) < ($this->days / 2)) {
			$this->matchups = $this->getFirstHalfFixtures($this->days / 2, $this->matchups);
		}
		$this->getSecondHalfFixtures($this->days / 2);
	}
	private function permutationsPer2() {
		for($i = 0; $i < $this->num_teams; ++$i) {
			$home_team = $this->participating_teams[$i];
			$tmp_array = array(); 
			for($j = 0; $j < $this->num_teams; ++$j) {
				$away_team = $this->participating_teams[$j];
				if($home_team === $away_team) {
					continue;
				}
				else {
					$tmp_array[] = array($home_team, $away_team);
				}
			}
			$this->matchups[] = $tmp_array;
		}
	}
	private function getFirstHalfFixtures($first_half_len, $matchups_cpy) {
		$original_matchups = $matchups_cpy;
		for($i = 0; $i < $first_half_len; ++$i) {
			$start_time = time();
			while(true) {
				$all_matches_cpy = $matchups_cpy;
				$fixture = array();
				for($j = 0; $j < $this->matches_per_day; ++$j) {
					$random_home_team_index = array_rand($all_matches_cpy);
					$random_home_team_matches = $all_matches_cpy[ $random_home_team_index ];
					$random_match_index = array_rand($random_home_team_matches);
					$random_match = $random_home_team_matches[ $random_match_index ];
					$fixture[] = $random_match;
					$all_matches_cpy = $this->removeChosenTeamMatchesFromArray($all_matches_cpy, $random_match[0], $random_match[1]);
					if(count($fixture) == $this->matches_per_day) {
						$matchups_cpy=$this->registerMatches($matchups_cpy,$fixture);
						$this->fixtures[] = $fixture;
						break 2;
					}
					elseif (count($all_matches_cpy) == 0 && count($fixture) < $this->matches_per_day) {
						break;
					}
					if((time() - $start_time) > 1) {
						$this->fixtures = array();
						return $original_matchups;
					}
				}
			}
		}
		return $matchups_cpy;
	}
	private function removeChosenTeamMatchesFromArray($all_matches_cpy, $team_1, $team_2) {
		$remaining_team_count = count($all_matches_cpy);
		for ($i = 0; $i < $remaining_team_count; ++$i) {
			$remaining_matches_for_team_i_count = count($all_matches_cpy[$i]);
			for ($j = 0; $j < $remaining_matches_for_team_i_count; ++$j) {
				$match = $all_matches_cpy[$i][$j];
				if(is_int(array_search($team_1, $match)) || is_int(array_search($team_2, $match))) {
					unset($all_matches_cpy[$i][$j]);
				}
			}
			$all_matches_cpy[$i] = array_values($all_matches_cpy[$i]); 
		} 
		$all_matches_cpy = array_values( array_filter($all_matches_cpy) );

		return $all_matches_cpy; 
	}

	private function registerMatches($all_matchups, $fixture) {
		$fixture_count = count($fixture);
		for ($fixture_num = 0; $fixture_num < $fixture_count; ++$fixture_num) {
			$remaining_team_count = count($all_matchups);
			for ($i = 0; $i < $remaining_team_count ; ++$i) {
				$remaining_matches_for_team_i_count = count($all_matchups[$i]);
				for ($j = 0; $j < $remaining_matches_for_team_i_count ; ++$j) {
					$match = $all_matchups[$i][$j];
					if( 
						(($fixture[$fixture_num][0] === $match[0]) && ($fixture[$fixture_num][1] === $match[1])) 
						|| (($fixture[$fixture_num][0] === $match[1]) && ($fixture[$fixture_num][1] === $match[0]))
					)
					{
						unset($all_matchups[$i][$j]);
					}
				}
				$all_matchups[$i] = array_values($all_matchups[$i]);
			}
		}
		$all_matchups = array_values( array_filter($all_matchups) );

		return $all_matchups; 
	}
	private function getSecondHalfFixtures($second_half_len) {
		for ($i = $second_half_len; $i < $this->days; ++$i) {
			$this->fixtures[$i] = $this->fixtures[$i - $second_half_len];
			for ($j = 0; $j < count($this->fixtures[$i]); ++$j) {
				$this->fixtures[$i][$j][0] = $this->fixtures[$i-$second_half_len][$j][1];
				$this->fixtures[$i][$j][1] = $this->fixtures[$i-$second_half_len][$j][0];
			}
		}
	}
	public function getFixtures() {
		return $this->fixtures;
	}
}
?>