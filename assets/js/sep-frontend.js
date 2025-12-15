/* SEP (Smart Exam Platform) - Frontend JavaScript */

(function($) {
    'use strict';

    // Global SEP object
    window.SEP = window.SEP || {
        config: {},
        exam: {},
        timer: null,
        currentQuestion: 0,
        answers: {},
        startTime: null,
        immersiveModeActive: false
    };

    // Initialize SEP
    $(document).ready(function() {
        SEP.init();
    });

    $.extend(SEP, {
        init: function() {
            this.bindEvents();
            this.checkImmersiveMode();
            this.initTimer();
            this.initConfetti();
        },

        bindEvents: function() {
            // Start exam button
            $(document).on('click', '.sep-start-exam-btn', function(e) {
                e.preventDefault();
                const examId = $(this).data('exam-id');
                SEP.startExam(examId);
            });

            // Next/Previous question buttons
            $(document).on('click', '.sep-next-question', function(e) {
                e.preventDefault();
                SEP.nextQuestion();
            });
            
            $(document).on('click', '.sep-prev-question', function(e) {
                e.preventDefault();
                SEP.prevQuestion();
            });

            // Option selection
            $(document).on('change', '.sep-option-input', function() {
                const questionIndex = $(this).data('question-index');
                const selectedValue = $(this).val();
                
                // Store answer
                SEP.answers[questionIndex] = selectedValue;
                
                // Update navigator
                SEP.updateNavigatorItem(questionIndex, true);
                
                // Highlight selected option
                $('.sep-question[data-question-index="' + questionIndex + '"] .sep-option').removeClass('selected');
                $(this).closest('.sep-option').addClass('selected');
            });

            // Question navigator item click
            $(document).on('click', '.sep-question-nav-item', function() {
                const index = $(this).data('question-index');
                SEP.goToQuestion(index);
            });

            // Submit exam
            $(document).on('click', '.sep-submit-exam', function(e) {
                e.preventDefault();
                SEP.submitExam();
            });

            // Review flag
            $(document).on('click', '.sep-flag-review', function() {
                const questionIndex = $(this).data('question-index');
                $(this).toggleClass('review');
                SEP.updateNavigatorItem(questionIndex, null, $(this).hasClass('review'));
            });

            // Share buttons
            $(document).on('click', '.sep-share-btn', function(e) {
                e.preventDefault();
                const platform = $(this).data('platform');
                const resultData = $(this).data('result-data');
                SEP.shareResult(platform, resultData);
            });

            // Exit immersive mode
            $(document).on('keydown', function(e) {
                if (SEP.immersiveModeActive && e.keyCode === 27) { // ESC key
                    SEP.exitImmersiveMode();
                }
            });
        },

        checkImmersiveMode: function() {
            if ($('.sep-immersive-mode').length) {
                this.enterImmersiveMode();
            }
        },

        enterImmersiveMode: function() {
            // Add immersive mode class to body
            $('body').addClass('sep-immersive-mode-active');
            
            // Hide WordPress admin bar and other elements
            $('#wpadminbar, .site-header, .navbar, .sidebar, .widget-area, .site-footer').hide();
            
            // Set fixed positioning for exam container
            $('.sep-exam-container').css({
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100vh',
                'overflow-y': 'auto',
                'z-index': '9999'
            });
            
            this.immersiveModeActive = true;
        },

        exitImmersiveMode: function() {
            // Remove immersive mode class
            $('body').removeClass('sep-immersive-mode-active');
            
            // Show hidden elements
            $('#wpadminbar, .site-header, .navbar, .sidebar, .widget-area, .site-footer').show();
            
            // Reset exam container positioning
            $('.sep-exam-container').css({
                'position': 'static',
                'top': 'auto',
                'left': 'auto',
                'width': 'auto',
                'height': 'auto',
                'overflow-y': 'visible'
            });
            
            this.immersiveModeActive = false;
        },

        startExam: function(examId) {
            // Show loading overlay
            this.showLoading();

            // Prepare data
            const data = {
                action: 'sep_start_exam',
                exam_id: examId,
                nonce: sep_ajax.nonce
            };

            // Make AJAX request
            $.post(sep_ajax.ajax_url, data)
                .done(function(response) {
                    if (response.success) {
                        SEP.exam = response.data;
                        SEP.startTime = new Date();
                        
                        // Render exam
                        SEP.renderExam();
                        
                        // Start timer
                        SEP.startTimer();
                        
                        // Enter immersive mode
                        if (!SEP.immersiveModeActive) {
                            SEP.enterImmersiveMode();
                        }
                    } else {
                        alert(response.data || 'Error starting exam');
                    }
                })
                .fail(function() {
                    alert('Error connecting to server');
                })
                .always(function() {
                    SEP.hideLoading();
                });
        },

        renderExam: function() {
            // Build exam HTML
            let examHtml = '<div class="sep-exam-header">';
            examHtml += '<h2 class="sep-exam-title">' + this.exam.title + '</h2>';
            examHtml += '<div class="sep-exam-timer" id="sep-exam-timer">00:00:00</div>';
            examHtml += '</div>';

            // Add question navigator
            examHtml += '<div class="sep-question-navigator">';
            examHtml += '<h3>Question Navigator</h3>';
            examHtml += '<div class="sep-question-nav-grid">';
            
            for (let i = 0; i < this.exam.questions.length; i++) {
                examHtml += '<div class="sep-question-nav-item" data-question-index="' + i + '">' + (i + 1) + '</div>';
            }
            
            examHtml += '</div></div>';

            // Add questions
            examHtml += '<div class="sep-questions-container">';
            
            for (let i = 0; i < this.exam.questions.length; i++) {
                const q = this.exam.questions[i];
                examHtml += this.renderQuestion(q, i);
            }
            
            examHtml += '</div>';

            // Add navigation controls
            examHtml += '<div class="sep-navigation">';
            examHtml += '<button class="sep-btn sep-btn-secondary sep-prev-question" disabled>Previous</button>';
            examHtml += '<button class="sep-btn sep-btn-primary sep-next-question">Next</button>';
            examHtml += '<button class="sep-btn sep-btn-success sep-submit-exam">Submit Exam</button>';
            examHtml += '</div>';

            // Replace content
            $('.sep-exam-container').html(examHtml);

            // Show first question
            this.showQuestion(0);
        },

        renderQuestion: function(question, index) {
            let html = '<div class="sep-question" data-question-index="' + index + '" style="display:none;">';
            html += '<span class="sep-question-number">Question ' + (index + 1) + '</span>';
            html += '<div class="sep-question-content">' + question.content + '</div>';
            
            html += '<div class="sep-options-container">';
            
            const options = ['a', 'b', 'c', 'd'];
            if (question.option_e_enabled) {
                options.push('e');
            }
            
            for (const opt of options) {
                if (question.options[opt]) {
                    const optionValue = opt.toUpperCase();
                    html += '<div class="sep-option">';
                    html += '<input type="radio" name="question_' + index + '" value="' + opt + '" class="sep-option-input" data-question-index="' + index + '" id="opt_' + index + '_' + opt + '">';
                    html += '<label for="opt_' + index + '_' + opt + '">' + optionValue + '. ' + question.options[opt] + '</label>';
                    html += '</div>';
                }
            }
            
            html += '</div>';
            
            // Add review flag
            html += '<div class="sep-review-section">';
            html += '<label><input type="checkbox" class="sep-flag-review" data-question-index="' + index + '"> Mark for Review</label>';
            html += '</div>';
            
            html += '</div>';
            
            return html;
        },

        showQuestion: function(index) {
            // Hide all questions
            $('.sep-question').hide();
            
            // Show current question
            $('.sep-question[data-question-index="' + index + '"]').show();
            
            // Update navigation buttons
            $('.sep-prev-question').prop('disabled', index === 0);
            $('.sep-next-question').text(index === (this.exam.questions.length - 1) ? 'Submit' : 'Next');
            
            // Update navigator highlight
            $('.sep-question-nav-item').removeClass('current');
            $('.sep-question-nav-item[data-question-index="' + index + '"]').addClass('current');
            
            // Scroll to top of question
            $('.sep-exam-container').scrollTop(0);
            
            this.currentQuestion = index;
        },

        nextQuestion: function() {
            if (this.currentQuestion < this.exam.questions.length - 1) {
                this.showQuestion(this.currentQuestion + 1);
            } else {
                // If on last question, submit exam
                this.submitExam();
            }
        },

        prevQuestion: function() {
            if (this.currentQuestion > 0) {
                this.showQuestion(this.currentQuestion - 1);
            }
        },

        goToQuestion: function(index) {
            this.showQuestion(index);
        },

        updateNavigatorItem: function(index, answered = null, review = null) {
            const $item = $('.sep-question-nav-item[data-question-index="' + index + '"]');
            
            if (answered !== null) {
                if (answered) {
                    $item.addClass('answered');
                } else {
                    $item.removeClass('answered');
                }
            }
            
            if (review !== null) {
                if (review) {
                    $item.addClass('review');
                } else {
                    $item.removeClass('review');
                }
            }
        },

        initTimer: function() {
            // Timer functionality will be implemented here
        },

        startTimer: function() {
            // Clear any existing timer
            if (this.timer) {
                clearInterval(this.timer);
            }

            // Set initial time based on exam duration
            let totalSeconds = parseInt(this.exam.duration) * 60; // Convert minutes to seconds
            
            // Update timer display
            this.updateTimerDisplay(totalSeconds);

            // Start countdown
            this.timer = setInterval(() => {
                totalSeconds--;
                
                if (totalSeconds <= 0) {
                    // Time's up!
                    clearInterval(this.timer);
                    this.timeUp();
                    return;
                }
                
                this.updateTimerDisplay(totalSeconds);
                
                // Add warning class when time is running out (last 5 minutes)
                if (totalSeconds <= 300) { // 5 minutes
                    $('#sep-exam-timer').addClass('warning');
                } else {
                    $('#sep-exam-timer').removeClass('warning');
                }
            }, 1000);
        },

        updateTimerDisplay: function(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            const timeString = 
                (hours ? hours.toString().padStart(2, '0') + ':' : '') +
                minutes.toString().padStart(2, '0') + ':' + 
                secs.toString().padStart(2, '0');
                
            $('#sep-exam-timer').text(timeString);
        },

        timeUp: function() {
            // Auto-submit exam when time runs out
            alert('Time is up! Your exam will be submitted automatically.');
            this.submitExam(true); // Force submit
        },

        submitExam: function(force = false) {
            if (!force) {
                if (!confirm('Are you sure you want to submit your exam?')) {
                    return;
                }
            }

            // Show loading overlay
            this.showLoading();

            // Calculate time taken
            const endTime = new Date();
            const timeTaken = Math.round((endTime - this.startTime) / 1000);

            // Prepare data
            const data = {
                action: 'sep_submit_exam',
                attempt_id: this.exam.attempt_id,
                answers: this.answers,
                time_taken: timeTaken,
                nonce: sep_ajax.nonce
            };

            // Make AJAX request
            $.post(sep_ajax.ajax_url, data)
                .done(function(response) {
                    if (response.success) {
                        // Stop timer
                        if (SEP.timer) {
                            clearInterval(SEP.timer);
                        }
                        
                        // Show results
                        SEP.showResults(response.data);
                    } else {
                        alert(response.data || 'Error submitting exam');
                    }
                })
                .fail(function() {
                    alert('Error connecting to server');
                })
                .always(function() {
                    SEP.hideLoading();
                });
        },

        showResults: function(resultsData) {
            // Build results HTML
            let resultsHtml = '<div class="sep-result-summary">';
            
            // Score circle
            const scoreClass = resultsData.passed ? 'pass' : 'fail';
            resultsHtml += '<div class="sep-score-circle ' + scoreClass + '">' + resultsData.percentage + '%</div>';
            
            resultsHtml += '<h2>' + (resultsData.passed ? 'Congratulations!' : 'Keep Practicing!') + '</h2>';
            resultsHtml += '<p>You scored ' + resultsData.score + ' out of ' + resultsData.total + ' (' + resultsData.percentage + '%)</p>';
            resultsHtml += '<p>' + resultsData.correct_count + ' out of ' + resultsData.total_questions + ' questions correct</p>';
            
            resultsHtml += '</div>';

            // Metrics
            resultsHtml += '<div class="sep-result-metrics">';
            resultsHtml += '<div class="sep-metric-card"><div class="sep-metric-value">' + resultsData.score + '/' + resultsData.total + '</div><div class="sep-metric-label">Score</div></div>';
            resultsHtml += '<div class="sep-metric-card"><div class="sep-metric-value">' + resultsData.percentage + '%</div><div class="sep-metric-label">Percentage</div></div>';
            resultsHtml += '<div class="sep-metric-card"><div class="sep-metric-value">' + resultsData.correct_count + '/' + resultsData.total_questions + '</div><div class="sep-metric-label">Correct Answers</div></div>';
            resultsHtml += '<div class="sep-metric-card"><div class="sep-metric-value">' + (resultsData.total_questions - resultsData.correct_count) + '</div><div class="sep-metric-label">Incorrect Answers</div></div>';
            resultsHtml += '</div>';

            // Share buttons
            resultsHtml += '<div class="sep-share-buttons">';
            resultsHtml += '<a href="#" class="sep-share-btn whatsapp" data-platform="whatsapp" data-result-data=\'' + JSON.stringify(resultsData) + '\'>';
            resultsHtml += '<i class="fab fa-whatsapp"></i> Share on WhatsApp';
            resultsHtml += '</a>';
            resultsHtml += '<a href="#" class="sep-share-btn telegram" data-platform="telegram" data-result-data=\'' + JSON.stringify(resultsData) + '\'>';
            resultsHtml += '<i class="fab fa-telegram"></i> Share on Telegram';
            resultsHtml += '</a>';
            resultsHtml += '</div>';

            // Replace content
            $('.sep-exam-container').html(resultsHtml);

            // Trigger after result hook
            $(document).trigger('sep_after_result', [resultsData]);

            // Show confetti if passed
            if (resultsData.passed) {
                this.triggerConfetti();
            }
        },

        shareResult: function(platform, resultData) {
            let shareUrl = '';
            let shareText = '';

            switch (platform) {
                case 'whatsapp':
                    shareText = 'I scored ' + resultData.percentage + '% on my ' + this.exam.title + '! Keep practicing for success. Check out this exam platform.';
                    shareUrl = 'https://wa.me/?text=' + encodeURIComponent(shareText);
                    break;
                    
                case 'telegram':
                    shareText = 'I scored ' + resultData.percentage + '% on my ' + this.exam.title + '! Keep practicing for success.';
                    shareUrl = 'https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent(shareText);
                    break;
                    
                default:
                    // Copy to clipboard
                    shareText = 'I scored ' + resultData.percentage + '% on my ' + this.exam.title + '! ' + window.location.href;
                    navigator.clipboard.writeText(shareText).then(function() {
                        alert('Share text copied to clipboard!');
                    });
                    return;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank');
            }
        },

        initConfetti: function() {
            // Create confetti container if it doesn't exist
            if ($('#sep-confetti-container').length === 0) {
                $('body').append('<div id="sep-confetti-container" class="sep-confetti-container"></div>');
            }
        },

        triggerConfetti: function() {
            // Only trigger if enabled in settings
            if (typeof sep_confetti_enabled !== 'undefined' && sep_confetti_enabled) {
                const container = document.getElementById('sep-confetti-container');
                container.classList.add('active');
                
                // Create a simple confetti effect using canvas-confetti or similar
                // For now, we'll just use a timeout to remove it
                setTimeout(() => {
                    container.classList.remove('active');
                }, 5000);
            }
        },

        showLoading: function() {
            if ($('#sep-loading-overlay').length === 0) {
                $('body').append('<div id="sep-loading-overlay" class="sep-loading-overlay"><div class="sep-loading"></div></div>');
            }
            $('#sep-loading-overlay').addClass('active');
        },

        hideLoading: function() {
            $('#sep-loading-overlay').removeClass('active');
        }
    });

    // Hook system for custom HTML/promotions
    $(document).on('sep_before_exam', function(event, examData) {
        // Custom HTML can be injected here via external plugins
        // This allows for lead forms, promotions, etc.
    });

    $(document).on('sep_after_submit', function(event, submitData) {
        // Custom HTML can be injected here via external plugins
        // This allows for CTAs, offers, etc. after submission
    });

    $(document).on('sep_after_result', function(event, resultData) {
        // Custom HTML can be injected here via external plugins
        // This allows for sharing, upsells, etc. after results
    });

})(jQuery);