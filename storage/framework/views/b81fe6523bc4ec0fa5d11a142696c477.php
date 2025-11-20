

<?php $__env->startSection('title', 'Dashboard Organisateur'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; min-height: calc(100vh - 80px); background: #000000;">
    
    <!-- Sidebar -->
    <div style="width: 280px; background: #111111; border-right: 1px solid #333333;">
        <!-- Admin Profile -->
        <div style="padding: 2rem; text-align: center; border-bottom: 1px solid #333333;">
            <div style="width: 60px; height: 60px; background: #0ea5e9; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: #000000; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.5rem;">
                AA
            </div>
            <div style="font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">ADMIN ADMIN</div>
            <div style="font-size: 0.8rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px;">Admin</div>
        </div>
        
        <!-- Navigation -->
        <div style="padding: 2rem;">
            <div style="margin-bottom: 2rem;">
                <div style="color: #666666; font-size: 0.8rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'Oswald', sans-serif;">NAVIGATION</div>
                <div style="background: #0ea5e9; color: #000000; padding: 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">
                    ■ GESTION DE VOS ÉPREUVES
                </div>
                <div style="color: #cccccc; font-size: 0.9rem; line-height: 2;">
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Administration (<?php echo e($events->count()); ?>)</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Import calendriers</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Annonces</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Gestion des catégories</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Gestion des bannières</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Gestion des fichiers</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222; cursor: pointer;" onclick="window.location.href='<?php echo e(route('organizer.create')); ?>'">
                        <span style="color: #0ea5e9;">◦ Création d'une épreuve</span>
                    </div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Liste de vos épreuves</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Épreuves passées</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Épreuves < 1 an</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Épreuves en cours</div>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid #222222;">◦ Épreuves à venir</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; background: #0c0c0c;">
        <!-- Header -->
        <div style="padding: 2rem; border-bottom: 1px solid #333333;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-family: 'Oswald', sans-serif; font-size: 1.8rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        LISTE DE MES ÉPREUVES
                    </h2>
                    <small style="color: #cccccc;"><?php echo e($events->count()); ?> épreuves sélectionnées</small>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <span style="background: #666666; color: #ffffff; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Accueil</span>
                    <span style="background: #0ea5e9; color: #000000; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Liste de mes épreuves</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Row -->
        <div style="display: flex; gap: 2rem; padding: 2rem; border-bottom: 1px solid #333333;">
            <!-- Quick Access -->
            <div style="flex: 2; background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 1px;">Accès rapide épreuves (ADMIN)</h4>
                <select style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #cccccc; font-family: 'Roboto', sans-serif;">
                    <option>Choisir ...</option>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option><?php echo e($event->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <!-- Quick Actions -->
            <div style="flex: 1; background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 1px;">Actions rapides (ADMIN)</h4>
                <div style="display: flex; gap: 0.5rem;">
                    <button style="background: #0ea5e9; color: #000000; border: none; padding: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer;">💰</button>
                    <button style="background: #22c55e; color: #000000; border: none; padding: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer;">💰</button>
                    <button style="background: #ef4444; color: #ffffff; border: none; padding: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer;">💰</button>
                    <button style="background: #f59e0b; color: #000000; border: none; padding: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer;">📊</button>
                    <button style="background: #8b5cf6; color: #ffffff; border: none; padding: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer;">✉️</button>
                </div>
            </div>
        </div>

        <!-- Participant Search -->
        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem; margin: 2rem; margin-bottom: 0;">
            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 1px;">Recherche rapide d'un participant</h4>
            <input type="text" placeholder="Entrer son nom et/ou prénom" style="width: 300px; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
        </div>

        <!-- Events Table -->
        <div style="margin: 2rem; background: #111111; border: 1px solid #333333; overflow: hidden;">
            <div style="background: #1a1a1a; color: #ffffff; padding: 1rem; border-bottom: 1px solid #333333;">
                <h4 style="font-family: 'Oswald', sans-serif; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Épreuves</h4>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #0c0c0c; border-bottom: 1px solid #333333;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-right: 1px solid #333333;">#</th>
                        <th style="padding: 1rem; text-align: left; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-right: 1px solid #333333;">Nom de l'épreuve</th>
                        <th style="padding: 1rem; text-align: center; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-right: 1px solid #333333;">Vérifications</th>
                        <th style="padding: 1rem; text-align: center; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-right: 1px solid #333333;">Date de départ</th>
                        <th style="padding: 1rem; text-align: center; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-right: 1px solid #333333;">Date de fin</th>
                        <th style="padding: 1rem; text-align: center; font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-bottom: 1px solid #333333;">
                            <td style="padding: 1.5rem 1rem; color: #cccccc; border-right: 1px solid #333333;"><?php echo e($event->id); ?></td>
                            <td style="padding: 1.5rem 1rem; border-right: 1px solid #333333;">
                                <strong style="font-family: 'Oswald', sans-serif; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;"><?php echo e($event->name); ?></strong><br>
                                <small style="color: #cccccc;"><?php echo e($event->location); ?> (<?php echo e($event->department); ?>)</small><br>
                                <?php if($event->status == 'upcoming'): ?>
                                    <span style="background: #ef4444; color: #ffffff; padding: 0.25rem 0.75rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem; display: inline-block;">Épreuve non ouverte</span>
                                <?php elseif($event->status == 'open'): ?>
                                    <span style="background: #22c55e; color: #000000; padding: 0.25rem 0.75rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem; display: inline-block;">Inscriptions ouvertes</span>
                                <?php elseif($event->status == 'closed'): ?>
                                    <span style="background: #f59e0b; color: #000000; padding: 0.25rem 0.75rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.5rem; display: inline-block;">Inscriptions fermées</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1.5rem 1rem; text-align: center; border-right: 1px solid #333333;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                                    <div style="text-align: center;">
                                        <div style="background: #22c55e; color: #000000; padding: 0.5rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem; font-weight: 600;">INSCRITS</div>
                                        <div style="color: #22c55e; font-weight: 700;">0 0</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="background: #6b7280; color: #ffffff; padding: 0.5rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem; font-weight: 600;">CERTIFICATS</div>
                                        <div style="color: #6b7280; font-weight: 700;">0 0</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="background: #f59e0b; color: #000000; padding: 0.5rem; font-family: 'Oswald', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem; font-weight: 600;">AUTO. PARENTALE</div>
                                        <div style="color: #f59e0b; font-weight: 700;">0 0</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1.5rem 1rem; text-align: center; color: #cccccc; border-right: 1px solid #333333;"><?php echo e($event->event_date->format('d/m/Y')); ?></td>
                            <td style="padding: 1.5rem 1rem; text-align: center; color: #cccccc; border-right: 1px solid #333333;"><?php echo e($event->event_date->format('d/m/Y')); ?></td>
                            <td style="padding: 1.5rem 1rem; text-align: center;">
                                <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                                    <div style="display: flex; gap: 0.25rem;">
                                        <button style="background: #17a2b8; color: #ffffff; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">🔍</button>
                                        <button style="background: #22c55e; color: #000000; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">🔧</button>
                                        <button style="background: #8b5cf6; color: #ffffff; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">⚙️</button>
                                    </div>
                                    <div style="display: flex; gap: 0.25rem;">
                                        <button style="background: #f59e0b; color: #000000; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">📊</button>
                                        <button style="background: #10b981; color: #000000; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">📋</button>
                                        <button style="background: #6b7280; color: #ffffff; border: none; padding: 0.5rem; font-size: 0.8rem; cursor: pointer;">📝</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="padding: 3rem; text-align: center; color: #cccccc;">
                                <div style="font-size: 2rem; margin-bottom: 1rem; color: #333333;">■</div>
                                Aucune épreuve créée. <a href="<?php echo e(route('organizer.create')); ?>" style="color: #0ea5e9; text-decoration: none;">Créer votre première épreuve</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
input:focus, select:focus {
    outline: none;
    border-color: #0ea5e9;
}

button:hover {
    transform: translateY(-1px);
}

tr:hover {
    background: #1a1a1a;
}

input::placeholder {
    color: #666666;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH U:\DEV\ats-sport\resources\views/organizer/dashboard.blade.php ENDPATH**/ ?>