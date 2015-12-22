<?php namespace App\Http\Controllers;

use Storage as Storage;
use Illuminate\Http\Request as Request;
use App\Models\Project as Project;
use App\Models\Blueprint as Blueprint;
use App\Models\Category as Category;
use App\Models\City as City;
use App\Models\Model as Model;
use App\Models\Order as Order;

class ProjectController extends Controller {
	
	public function updateProject(Request $request, $id) {
	    $project = $this->getSecureProjectById($id);
		$project->update(\Input::all());
		
		if (\Input::has('category_id')) {
			$categoryId = \Input::get('category_id');
			$category = Category::findOrFail($categoryId);
			$project->category()->associate($category);
		}
		
		if (\Input::has('city_id')) {
			$cityId = \Input::get('city_id');
			$city = City::findOrFail($cityId);
			$project->city()->associate($city);
		}
		
		if ($request->file('poster')) {
			$posterUrl = $this->uploadPosterImage($request, $project);
			$project->setAttribute('poster_url', $posterUrl);
		}
		
		$project->save();
		return $project;
	}
	
	private function uploadPosterImage($request, $project) {
		$posterUrlPartial = Model::S3_POSTER_DIRECTORY . $project->id . '.jpg';
		
		Storage::put(
			$posterUrlPartial,
			file_get_contents($request->file('poster')->getRealPath())
		);
		
		return Model::S3_BASE_URL . $posterUrlPartial;
	}
	
	public function uploadStoryImage(Request $request, $id) {
	    $project = $this->getSecureProjectById($id);
		
		$file = $request->file('image');
		$originalName = $file->getClientOriginalName();
		$hashedName = md5($originalName);
		$storyUrlPartial = Model::S3_STORY_DIRECTORY . $project->id . '/' . $hashedName . '.jpg';
		
		Storage::put(
			$storyUrlPartial,
			file_get_contents($file->getRealPath())
		);
		
		return Model::S3_BASE_URL . $storyUrlPartial;
	}
	
	public function uploadNewsImage(Request $request, $id) {
	    $project = $this->getSecureProjectById($id);
		
		$file = $request->file('image');
		$originalName = $file->getClientOriginalName();
		$hashedName = md5($originalName);
		$newsUrlPartial = Model::S3_NEWS_DIRECTORY . $project->id . '/' . $hashedName . '.jpg';
		
		Storage::put(
			$newsUrlPartial,
			file_get_contents($file->getRealPath())
		);
		
		return Model::S3_BASE_URL . $newsUrlPartial;
	}
	
	public function getUpdateFormById($id) {
	    $project = $this->getSecureProjectById($id);
		return $this->returnUpdateForm($project);
	} 
	
	public function getUpdateFormByCode($code) {
		$project = $this->getProjectByBlueprintCode($code);
		return $this->returnUpdateForm($project);
	}
	
	private function returnUpdateForm($project) {
		$project->load('tickets');
		$tab = $this->getValidUpdateFormTab();
		return view('project.form', [
			'selected_tab' => $tab,
			'project' => $project,
			'categories' => Category::orderBy('id')->get(),
			'cities' => City::orderBy('id')->get()
		]);
	}
	
	private function getValidUpdateFormTab() {
		$tab = \Input::get('tab');
		switch ($tab) {
			case 'base':
			case 'reward':
			case 'ticket':
			case 'poster':
			case 'story':
			case 'creator':
				return $tab;
			default:
				return 'base';
		}
	}
	
	public function getProjects() {
		$projects = [];
		$tab = $this->getValidExploreTab();
		switch ($tab) {
			default:
			case 'all':
				$projects = Project::where('state', 4)->get();
				break;
				
			case 'funding':
			case 'sale':
				$projects = Project::where('type', '=', $tab)->where('state', 4)->get();
				break;
				
			case 'date':
				$projects = Project::where('state', 4)->get();
				break;
		}
		
		return view('project.explore', [
			'selected_tab' => $tab,
			'projects' => $projects
		]);
	}
	
	private function getValidExploreTab() {
		$tab = \Input::get('tab');
		switch ($tab) {
			case 'all':
			case 'funding':
			case 'sale':
			case 'date':
				return $tab;
			default:
				return 'all';
		}
	}
    
    private function getSecureProjectById($id) {
        $project = Project::findOrFail($id);
        \Auth::user()->checkOwnership($project);
        return $project;
    }
	
	private function getApprovedProject($project) {
		if ($project->state !== Project::STATE_APPROVED) {
            if (\Auth::check()) {
                \Auth::user()->checkOwnership($project);
            } else {
                throw new \App\Exceptions\OwnershipException;
            } 
        }
		return $project;
	}
	
	public function getProjectById($id) {
		$project = Project::findOrFail($id);
		$project = $this->getApprovedProject($project);
		return $this->getProjectDetailView($project);
	}
	
	public function getProjectByAlias($alias) {
		$project = Project::where('alias', '=', $alias)->firstOrFail();
		$project = $this->getApprovedProject($project);
		return $this->getProjectDetailView($project);
	}

    private function getProjectDetailView($project) {
		$project->load(['category', 'city', 'tickets']);
        return view('project.detail', [
            'project' => $project,
            'is_master' => \Auth::check() && \Auth::user()->isOwnerOf($project)
        ]);
    }
	
	public function validateProjectAlias($alias) {
		$pattern = '/^[a-zA-Z]{1}[a-zA-Z0-9-_]{3,63}/';
		$match = preg_match($pattern, $alias);
		if ($match) {
			$project = Project::where('alias', '=', $alias)->first();
			if (!$project) {
				return "";
			} else {
				return \App::abort(409);
			}
		} else {
			return \App::abort(422);
		}
	}
	
	public function submitProject($id) {
	    $project = $this->getSecureProjectById($id);
		$project->submit();
		return $project;
	}
	
	private function createProject() {
		$project = new Project(\Input::all());
		$project->user()->associate(\Auth::user());
		$project->setAttribute('story', ' ');
		$project->save();
		return $project;
	}
	
	private function getProjectByBlueprintCode($code) {
		$blueprint = Blueprint::findByCode($code);
		if (!$blueprint->approved) {
			throw new \Exception;
		}
		
		\Auth::user()->checkOwnership($blueprint);
		
		if ($blueprint->hasProjectCreated()) {
			return $blueprint->project()->first();
		} else {
			$project = $this->createProject();
			$blueprint->project()->associate($project);
			$blueprint->save();
			return $project;
		}
	}
	
	public function getNews($id) {
		$project = Project::findOrFail($id);
		return $project->news()->get();
	}
	
	public function getSupporters($id) {
		$project = Project::findOrFail($id);
		return $project->supporters()->with(['user', 'ticket'])->get();
	}
	
	public function getComments($id) {
		$project = Project::findOrFail($id);
		return $project->comments()->with('user', 'comments', 'comments.user')->get();
	}
	
	public function getOrders($id) {
		$project = $this->getSecureProjectById($id);
		return $project->orders()->with('user')->get();
	}

}
