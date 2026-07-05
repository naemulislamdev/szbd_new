<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class CacheController extends Controller
{
    /**
     * Available cache commands
     */
    protected array $cacheTypes = [
        'app'      => ['command' => 'cache:clear',    'label' => 'Application Cache'],
        'route'    => ['command' => 'route:clear',    'label' => 'Route Cache'],
        'config'   => ['command' => 'config:clear',   'label' => 'Config Cache'],
        'view'     => ['command' => 'view:clear',     'label' => 'View Cache'],
    ];

    protected array $optimizeTypes = [
        'route_cache'   => ['command' => 'route:cache',   'label' => 'Route Cache Rebuild'],
        'config_cache'  => ['command' => 'config:cache',  'label' => 'Config Cache Rebuild'],
        'optimize'      => ['command' => 'optimize',      'label' => 'Full Optimize'],
    ];

    /**
     * Dashboard view
     */
    public function index()
    {
        $cacheStatus = $this->getCacheStatus();
        return view('admin.cache.index', compact('cacheStatus'));
    }

    /**
     * Single cache clear
     */
    public function clear(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys($this->cacheTypes)),
        ]);

        $type = $request->input('type');
        $info = $this->cacheTypes[$type];

        try {
            Artisan::call($info['command']);
            $output = trim(Artisan::output());

            return response()->json([
                'success' => true,
                'label'   => $info['label'],
                'output'  => $output ?: $info['label'] . ' cleared successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'label'   => $info['label'],
                'output'  => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all caches at once
     */
    public function clearAll()
    {
        $results = [];

        foreach ($this->cacheTypes as $key => $info) {
            try {
                Artisan::call($info['command']);
                $results[] = [
                    'key'     => $key,
                    'label'   => $info['label'],
                    'success' => true,
                    'output'  => trim(Artisan::output()) ?: $info['label'] . ' cleared.',
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'key'     => $key,
                    'label'   => $info['label'],
                    'success' => false,
                    'output'  => $e->getMessage(),
                ];
            }
        }

        $allSuccess = collect($results)->every(fn($r) => $r['success']);

        return response()->json([
            'success' => $allSuccess,
            'results' => $results,
        ]);
    }

    /**
     * Run optimize commands (cache rebuild)
     */
    public function optimize(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys($this->optimizeTypes)),
        ]);

        $type = $request->input('type');
        $info = $this->optimizeTypes[$type];

        try {
            Artisan::call($info['command']);
            $output = trim(Artisan::output());

            return response()->json([
                'success' => true,
                'label'   => $info['label'],
                'output'  => $output ?: $info['label'] . ' completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'label'   => $info['label'],
                'output'  => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check which caches are currently active
     */
    protected function getCacheStatus(): array
    {
        return [
            'route'    => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'config'   => file_exists(base_path('bootstrap/cache/config.php')),
            'view'     => count(glob(storage_path('framework/views/*.php'))) > 0,
            'compiled' => file_exists(base_path('bootstrap/cache/packages.php')), // ← এটা missing ছিল
        ];
    }
}
