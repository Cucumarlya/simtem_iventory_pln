/**
 * Dashboard Admin JavaScript
 * SINVOSAR - Sistem Informasi Pengelolaan Material Sarana
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard Admin - Initializing...');
    
    // ===== INITIALIZE FEATHER ICONS =====
    function initFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
            console.log('✓ Feather icons initialized');
        } else {
            console.warn('Feather icons not found, loading dynamically...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js';
            script.onload = function() {
                feather.replace();
                console.log('✓ Feather icons loaded dynamically');
            };
            document.head.appendChild(script);
        }
    }
    
    initFeatherIcons();
    
    // ===== INITIALIZE CHART =====
    function initChart() {
        const ctx = document.getElementById('transactionChart');
        if (!ctx) {
            console.warn('Chart canvas not found');
            return null;
        }
        
        console.log('✓ Initializing chart...');
        
        // Chart data
        const bulanIniData = {
            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            penerimaan: [45, 52, 48, 60],
            pengeluaran: [30, 40, 35, 45]
        };
        
        const tahunIniData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            penerimaan: [85, 78, 92, 88, 95, 87, 82, 90, 85, 88, 92, 95],
            pengeluaran: [45, 52, 48, 60, 55, 58, 50, 62, 57, 60, 65, 68]
        };
        
        try {
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bulanIniData.labels,
                    datasets: [
                        {
                            label: 'Penerimaan',
                            data: bulanIniData.penerimaan,
                            backgroundColor: '#10b981',
                            borderColor: '#059669',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.5,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Pengeluaran',
                            data: bulanIniData.pengeluaran,
                            backgroundColor: '#3b82f6',
                            borderColor: '#2563eb',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.5,
                            categoryPercentage: 0.7,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#374151',
                                font: {
                                    size: 11,
                                    weight: '600'
                                },
                                padding: 15,
                                boxWidth: 8,
                                boxHeight: 8
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 10,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: '#f3f4f6',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 10,
                                    weight: '500'
                                },
                                maxTicksLimit: 5
                            },
                            beginAtZero: true,
                            suggestedMax: 65
                        }
                    }
                }
            });
            
            console.log('✓ Chart initialized successfully');
            
            // ===== CHART SWITCHER =====
            const btnBulanIni = document.getElementById('btnBulanIni');
            const btnTahunIni = document.getElementById('btnTahunIni');
            
            if (btnBulanIni && btnTahunIni) {
                function updateChart(data, maxValue) {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.penerimaan;
                    chart.data.datasets[1].data = data.pengeluaran;
                    chart.options.scales.y.suggestedMax = maxValue;
                    chart.update();
                }
                
                function setActiveButton(activeBtn, inactiveBtn) {
                    activeBtn.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                    activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-50');
                    inactiveBtn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                    inactiveBtn.classList.add('text-gray-600', 'hover:bg-gray-50');
                }
                
                btnBulanIni.addEventListener('click', function(e) {
                    e.preventDefault();
                    updateChart(bulanIniData, 65);
                    setActiveButton(btnBulanIni, btnTahunIni);
                });
                
                btnTahunIni.addEventListener('click', function(e) {
                    e.preventDefault();
                    updateChart(tahunIniData, 100);
                    setActiveButton(btnTahunIni, btnBulanIni);
                });
                
                console.log('✓ Chart switcher initialized');
            }
            
            return chart;
            
        } catch (error) {
            console.error('Error initializing chart:', error);
            return null;
        }
    }
    
    const chart = initChart();
    
    // ===== QUICK ACTIONS INTERACTIONS =====
    function initQuickActions() {
        const quickActions = document.querySelectorAll('.quick-action');
        
        quickActions.forEach(action => {
            // Hover effect
            action.addEventListener('mouseenter', function() {
                const icon = this.querySelector('div[class*="bg-"]:first-child');
                if (icon) {
                    icon.style.transform = 'scale(1.05)';
                    icon.style.transition = 'transform 0.2s ease';
                }
            });
            
            action.addEventListener('mouseleave', function() {
                const icon = this.querySelector('div[class*="bg-"]:first-child');
                if (icon) {
                    icon.style.transform = 'scale(1)';
                }
            });
            
            // Click effect
            action.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        console.log(`✓ Quick actions initialized (${quickActions.length} items)`);
    }
    
    initQuickActions();
    
    // ===== STAT CARDS INTERACTIONS =====
    function initStatCards() {
        const statCards = document.querySelectorAll('.bg-gradient-to-br');
        
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
            
            // Add cursor pointer
            card.style.cursor = 'pointer';
        });
        
        console.log(`✓ Stat cards initialized (${statCards.length} cards)`);
    }
    
    initStatCards();
    
    // ===== TABLE ROW HOVER =====
    function initTableHover() {
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f9fafb';
                this.style.transition = 'background-color 0.15s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        console.log(`✓ Table hover initialized (${tableRows.length} rows)`);
    }
    
    initTableHover();
    
    // ===== RESPONSIVE HANDLING =====
    let resizeTimer;
    
    function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            console.log('Window resized:', window.innerWidth, 'x', window.innerHeight);
            
            // Update chart if exists
            if (chart) {
                chart.resize();
                console.log('✓ Chart resized');
            }
            
            // Update feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }, 250);
    }
    
    window.addEventListener('resize', handleResize);
    
    // ===== TOUCH DEVICE SUPPORT =====
    if ('ontouchstart' in window || navigator.maxTouchPoints) {
        console.log('Touch device detected, adding touch support...');
        
        const quickActions = document.querySelectorAll('.quick-action');
        quickActions.forEach(action => {
            action.addEventListener('touchstart', function() {
                this.style.opacity = '0.9';
            });
            
            action.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.opacity = '';
                }, 150);
            });
        });
        
        console.log('✓ Touch support added');
    }
    
    // ===== LOADING COMPLETE =====
    setTimeout(() => {
        document.body.classList.add('dashboard-loaded');
        console.log('✓ Dashboard fully loaded');
    }, 500);
});

// ===== ERROR HANDLING =====
window.addEventListener('error', function(e) {
    console.error('Dashboard Error:', e.message, e.filename, e.lineno);
});

// ===== HELPER FUNCTIONS =====
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'warning' ? 'bg-yellow-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// ===== DEBUG FUNCTIONS =====
window.dashboard = {
    reloadChart: function() {
        console.log('Reloading chart...');
        // Chart reload logic here
    },
    getStats: function() {
        return {
            cards: document.querySelectorAll('.bg-gradient-to-br').length,
            quickActions: document.querySelectorAll('.quick-action').length,
            tableRows: document.querySelectorAll('tbody tr').length
        };
    }
};

console.log('Dashboard Admin JS module loaded');