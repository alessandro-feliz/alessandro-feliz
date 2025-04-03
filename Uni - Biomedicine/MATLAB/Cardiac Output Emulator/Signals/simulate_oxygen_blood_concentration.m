function oxygen_blood_concentration = simulate_oxygen_blood_concentration(duration, avg, variation_percentage)
    % Calculate max and min amplitude of the signal
    max_amplitude = avg + avg * variation_percentage/100;
    min_amplitude = avg - avg * variation_percentage/100;

    % Generate random oxygen blood concentration values within the specified amplitudes
    oxygen_blood_concentration = rand(1, duration) * (max_amplitude - min_amplitude) + min_amplitude;
end
