function heart_rate = simulate_heart_rate(duration, min_rate, max_rate, variation_percentage)
    % Generate random baseline (between max and min)
    baseline = min_rate + (max_rate - min_rate) * rand();

    % Calculate max and min amplitude of the signal based in the baseline
    max_amplitude = baseline + baseline * variation_percentage/100;
    min_amplitude = baseline - baseline * variation_percentage/100;

    % Generate random heart rate values within the specified amplitudes
    heart_rate = rand(1, duration) * (max_amplitude - min_amplitude) + min_amplitude;
end
