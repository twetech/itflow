// Description: This file contains the JavaScript for the ticket time tracking feature

(function() {
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize variables
        var timerInterval = null;
        var isPaused = false;
        var ticketID = getCurrentTicketID();
        var elapsedSecs = getElapsedSeconds();

        updateRunningTicketsCount();

        function getCurrentTicketID() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('ticket_id');
        }

        function getLocalStorageKey(suffix) {
            return ticketID + "-" + suffix;
        }

        function getElapsedSeconds() {
            let storedStartTime = parseInt(localStorage.getItem(getLocalStorageKey("startTime")) || "0");
            let pausedTime = parseInt(localStorage.getItem(getLocalStorageKey("pausedTime")) || "0");
            if (!storedStartTime) return pausedTime;
            let timeSinceStart = Math.floor((Date.now() - storedStartTime) / 1000);
            return pausedTime + timeSinceStart;
        }

        function displayTime() {
            let totalSeconds = elapsedSecs;
            let hours = Math.floor(totalSeconds / 3600);
            totalSeconds %= 3600;
            let minutes = Math.floor(totalSeconds / 60);
            let seconds = totalSeconds % 60;

            document.getElementById("hours").value = pad(hours);
            document.getElementById("minutes").value = pad(minutes);
            document.getElementById("seconds").value = pad(seconds);
        }

        function pad(val) {
            return val < 10 ? "0" + val : val;
        }

        function countTime() {
            elapsedSecs++;
            displayTime();
        }

        function startTimer() {
            if (!localStorage.getItem(getLocalStorageKey("startTime"))) {
                localStorage.setItem(getLocalStorageKey("startTime"), Date.now().toString());
            }
            timerInterval = setInterval(countTime, 1000);
            isPaused = false;
            document.getElementById("startStopTimer").innerText = "Pause";
            updateRunningTicketsCount();
            localStorage.setItem("ticket-timer-running-" + ticketID, "true");

        }

        function pauseTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            let currentElapsed = getElapsedSeconds();
            localStorage.setItem(getLocalStorageKey("pausedTime"), currentElapsed.toString());
            localStorage.removeItem(getLocalStorageKey("startTime"));
            isPaused = true;
            document.getElementById("startStopTimer").innerText = "Start";
            updateRunningTicketsCount();
            localStorage.setItem("ticket-timer-running-" + ticketID, "false");

        }

        function clearTimeStorage() {
            localStorage.removeItem(getLocalStorageKey("startTime"));
            localStorage.removeItem(getLocalStorageKey("pausedTime"));
        }

        function resetTimer() {
            if (confirm("Are you sure you want to reset the timer?")) {
                clearInterval(timerInterval);
                timerInterval = null;
                elapsedSecs = 0;
                clearTimeStorage();
                displayTime();
                document.getElementById("startStopTimer").innerText = "Start";
            }
            localStorage.setItem("ticket-timer-running-" + ticketID, "false");
            updateRunningTicketsCount();
        }

        function forceResetTimer() {
            clearInterval(timerInterval);
            timerInterval = null;
            elapsedSecs = 0;
            clearTimeStorage();
            displayTime();
            document.getElementById("startStopTimer").innerText = "Start";
        }
        

        function handleInputFocus() {
            if (!isPaused) {
                pauseTimer();
            }
        }

        function updateTimeFromInput() {
            const hours = parseInt(document.getElementById("hours").value, 10) || 0;
            const minutes = parseInt(document.getElementById("minutes").value, 10) || 0;
            const seconds = parseInt(document.getElementById("seconds").value, 10) || 0;
            elapsedSecs = (hours * 3600) + (minutes * 60) + seconds;
            
            // Update local storage so the manually entered time is retained even if the page is reloaded.
            if (!timerInterval) {
                localStorage.setItem(getLocalStorageKey("pausedTime"), elapsedSecs.toString());
            } else {
                const newStartTime = Date.now() - (elapsedSecs * 1000);
                localStorage.setItem(getLocalStorageKey("startTime"), newStartTime.toString());
                localStorage.removeItem(getLocalStorageKey("pausedTime"));
            }
        }

        function updateRunningTicketsCount() {
            var runningTickets = parseInt(document.getElementById('runningTicketsCount').innerText, 10);

            if (!isPaused && timerInterval) {
                runningTickets += 1;
            } else {
                runningTickets = Math.max(0, runningTickets - 1);
            }

        document.getElementById('runningTicketsCount').innerText = runningTickets.toString();
        }   
        
        document.getElementById("hours").addEventListener('change', updateTimeFromInput);
        document.getElementById("minutes").addEventListener('change', updateTimeFromInput);
        document.getElementById("seconds").addEventListener('change', updateTimeFromInput);
        

        document.getElementById("hours").addEventListener('focus', handleInputFocus);
        document.getElementById("minutes").addEventListener('focus', handleInputFocus);
        document.getElementById("seconds").addEventListener('focus', handleInputFocus);

        document.getElementById("startStopTimer").addEventListener('click', function() {
            if (timerInterval === null) {
                startTimer();
            } else {
                pauseTimer();
            }
        });

        document.getElementById("resetTimer").addEventListener('click', function() {
            resetTimer();
        });

        document.getElementById("ticket_add_reply").addEventListener('click', function() {
            // Wait for other synchronous actions (if any) to complete before resetting the timer.
            setTimeout(forceResetTimer, 100); // 100ms delay should suffice, but you can adjust as needed.
        });

        try {
            displayTime();
            if (!localStorage.getItem(getLocalStorageKey("startTime")) && !localStorage.getItem(getLocalStorageKey("pausedTime"))) {
                // If first time, start the timer automatically
                startTimer();
            } else if (localStorage.getItem(getLocalStorageKey("startTime"))) {
                // Continue timer if it was running before
                startTimer();
            }
        } catch (error) {
            console.error("There was an issue initializing the timer:", error);
        }
    });
})();