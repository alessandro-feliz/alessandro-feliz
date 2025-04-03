function oxygen_consume = simulate_oxygen_consume(duration, avg, variation_percentage)
    % Calculate max and min amplitude of the signal
    max_amplitude = avg + avg * variation_percentage/100;
    min_amplitude = avg - avg * variation_percentage/100;

    % Generate random oxygen consume values within the specified amplitudes
    oxygen_consume = rand(1, duration) * (max_amplitude - min_amplitude) + min_amplitude;
end
