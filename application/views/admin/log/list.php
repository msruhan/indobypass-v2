<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i> Log Aktivitas
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Negara</th>
                                <th>IP Address</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)) : $no=1; foreach ($logs as $log) : ?>
                            <?php
                                // Lookup country code from IP (simple, via ip-api.com)
                                $countryCode = '';
                                $countryName = '';
                                if (!empty($log->ip_address)) {
                                    $ip = $log->ip_address;
                                    $cacheKey = 'flag_' . md5($ip);
                                    $flagCache = isset($_SESSION[$cacheKey]) ? $_SESSION[$cacheKey] : null;
                                    if ($flagCache) {
                                        list($countryCode, $countryName) = explode('|', $flagCache);
                                    } else {
                                        $json = @file_get_contents('http://ip-api.com/json/' . $ip . '?fields=countryCode,country');
                                        if ($json) {
                                            $data = json_decode($json, true);
                                            if (!empty($data['countryCode'])) {
                                                $countryCode = strtolower($data['countryCode']);
                                                $countryName = $data['country'];
                                                $_SESSION[$cacheKey] = $countryCode . '|' . $countryName;
                                            }
                                        }
                                    }
                                }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($log->username) ?></td>
                                <td><?= htmlspecialchars($log->activity) ?></td>
                                <td>
                                    <?php if ($countryCode): ?>
                                        <img src="https://flagcdn.com/16x12/<?= $countryCode ?>.png" alt="<?= htmlspecialchars($countryName) ?>" title="<?= htmlspecialchars($countryName) ?>" style="margin-right:4px;"> <?= htmlspecialchars($countryName) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($log->ip_address) ?></td>
                                <td><?= htmlspecialchars($log->created_at) ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center">Tidak ada data log.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
