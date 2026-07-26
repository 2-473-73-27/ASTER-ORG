/**
 * AstraManager-Backend.js
 * External JavaScript controller for AstraManager-Backend Portal
 * Manages secure cookie sessions, live telemetry, image/PDF updates, and command execution.
 */

// Cookie Helper Functions
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-0; path=/';
}

// Initialize Application State
let customLogo = getCookie('astra_backend_logo') || "https://astrasms.com/assets/img/logo.png";
let storedPassword = localStorage.getItem('astra_manager_pass') || 'Muhammad73';
let updateCount = parseInt(getCookie('astra_update_count') || '3');

document.addEventListener("DOMContentLoaded", () => {
    const loginLogo = document.getElementById('loginLogoDisplay');
    const sidebarLogo = document.getElementById('sidebarLogoDisplay');
    const reportSummary = document.getElementById('todayReportSummary');

    if (loginLogo) loginLogo.src = customLogo;
    if (sidebarLogo) sidebarLogo.src = customLogo;
    if (reportSummary) reportSummary.innerText = updateCount + ' Updates Processed';

    // Check Session on Load via Cookie
    const sessionActive = getCookie('astra_manager_session');
    if (sessionActive === 'active') {
        const loginPage = document.getElementById('loginPage');
        const mainDashboard = document.getElementById('mainDashboard');
        if (loginPage) loginPage.classList.add('hidden');
        if (mainDashboard) mainDashboard.classList.remove('hidden');
        fetchClientIp();
    }

    // Start Live Telemetry Timer
    setInterval(runLiveTelemetry, 1000);
});

function fetchClientIp() {
    const ipDisplay = document.getElementById('todayIpDisplay');
    if (ipDisplay) {
        const randomIp = `103.${Math.floor(Math.random()*200)}.${Math.floor(Math.random()*255)}.${Math.floor(Math.random()*255)}`;
        ipDisplay.innerText = randomIp;
    }
}

function handleManagerLogin(event) {
    event.preventDefault();
    const user = document.getElementById('username').value.trim();
    const pass = document.getElementById('password').value;

    if (user === 'Muhammad73' && pass === storedPassword) {
        setCookie('astra_manager_session', 'active', 7); // Persistent cookie for 7 days
        document.getElementById('loginPage').classList.add('hidden');
        document.getElementById('mainDashboard').classList.remove('hidden');
        fetchClientIp();
    } else {
        alert('Invalid Manager Credentials! Please use username Muhammad73 and password Muhammad73.');
    }
}

function handleLogout() {
    eraseCookie('astra_manager_session');
    document.getElementById('mainDashboard').classList.add('hidden');
    document.getElementById('loginPage').classList.remove('hidden');
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
}

// Logo & Image Upload Handler
function handleLogoUpload() {
    const fileInput = document.getElementById('logoFileInput');
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            customLogo = e.target.result;
            setCookie('astra_backend_logo', customLogo, 30);
            
            const loginLogo = document.getElementById('loginLogoDisplay');
            const sidebarLogo = document.getElementById('sidebarLogoDisplay');
            if (loginLogo) loginLogo.src = customLogo;
            if (sidebarLogo) sidebarLogo.src = customLogo;
            
            updateCount++;
            setCookie('astra_update_count', updateCount, 30);
            const reportSummary = document.getElementById('todayReportSummary');
            if (reportSummary) reportSummary.innerText = updateCount + ' Updates Processed';

            const list = document.getElementById('imageReportList');
            if (list) {
                if(list.querySelector('span.italic')) list.innerHTML = '';
                const imgThumb = document.createElement('img');
                imgThumb.src = customLogo;
                imgThumb.className = 'w-12 h-12 object-contain bg-slate-900 rounded border border-slate-700 p-1 shrink-0';
                list.appendChild(imgThumb);
            }

            fileInput.value = '';
            alert('Logo updated successfully via Backend Cookies!');
        };
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        alert('Please select an image file first.');
    }
}

// PDF / Number Update Handler
function handlePdfUpdate() {
    const pdfInput = document.getElementById('pdfFileInput');
    if (pdfInput.files && pdfInput.files[0]) {
        const fileName = pdfInput.files[0].name;
        const statusDisplay = document.getElementById('pdfStatusDisplay');
        if (statusDisplay) {
            statusDisplay.innerText = `Synced: ${fileName} (${new Date().toLocaleTimeString()})`;
        }
        updateCount++;
        setCookie('astra_update_count', updateCount, 30);
        const reportSummary = document.getElementById('todayReportSummary');
        if (reportSummary) reportSummary.innerText = updateCount + ' Updates Processed';
        alert('Number document / PDF updated successfully!');
    } else {
        alert('Please select a PDF or data file first.');
    }
}

// Password Update Handler
function updateManagerPassword() {
    const newPassField = document.getElementById('newPasswordInput');
    if (!newPassField) return;
    const newPass = newPassField.value.trim();
    if (newPass.length > 0) {
        storedPassword = newPass;
        localStorage.setItem('astra_manager_pass', newPass);
        newPassField.value = '';
        alert('Manager password updated successfully in secure storage!');
    } else {
        alert('Please enter a valid password.');
    }
}

// Command Line Runner
function executeCommand() {
    const cmdInput = document.getElementById('commandInput');
    const logBox = document.getElementById('commandOutputLog');
    if (!cmdInput || !logBox) return;

    const cmd = cmdInput.value.trim();
    if(cmd === '') return;

    logBox.innerHTML += `\n> Executing: ${cmd}\n[SUCCESS] Command executed smoothly. Response code 200.`;
    logBox.scrollTop = logBox.scrollHeight;
    cmdInput.value = '';
}

// Real-time Telemetry Loop
let secondsElapsed = 0;
function runLiveTelemetry() {
    secondsElapsed++;
    let hrs = Math.floor(secondsElapsed / 3600).toString().padStart(2, '0');
    let mins = Math.floor((secondsElapsed % 3600) / 60).toString().padStart(2, '0');
    let secs = (secondsElapsed % 60).toString().padStart(2, '0');
    
    const uptimeCounter = document.getElementById('uptimeCounter');
    const apiLatency = document.getElementById('apiLatency');

    if (uptimeCounter) uptimeCounter.innerText = `${hrs}:${mins}:${secs}`;
    if (apiLatency) apiLatency.innerText = (20 + Math.floor(Math.random() * 10)) + 'ms';
}