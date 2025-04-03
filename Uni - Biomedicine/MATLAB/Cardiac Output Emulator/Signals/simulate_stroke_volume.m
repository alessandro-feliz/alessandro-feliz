function stroke_volume = simulate_stroke_volume(duration, avg_stroke_volume, variation_percentage)
    % Calculate max and min amplitude of the signal based in the average stroke volume
    max_amplitude = avg_stroke_volume + avg_stroke_volume * variation_percentage/100;
    min_amplitude = avg_stroke_volume - avg_stroke_volume * variation_percentage/100;

    % Generate random stroke volume values within the specified amplitudes
    stroke_volume = rand(1, duration) * (max_amplitude - min_amplitude) + min_amplitude;
end
